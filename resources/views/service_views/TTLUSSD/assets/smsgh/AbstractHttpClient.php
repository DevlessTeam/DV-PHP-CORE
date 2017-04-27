<?php

require 'RequestHandler.php';
require 'RequestLogger.php';
require 'ConsoleLogger.php';
require 'HttpRequest.php';
require 'HttpGet.php';
require 'HttpPost.php';
require 'HttpDelete.php';
require 'HttpPut.php';
require 'HttpHead.php';
require 'HttpResponse.php';

/**
 * Description of AbstractHttpClient
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
abstract class AbstractHttpClient {

    const URLENCODED = "application/x-www-form-urlencoded;charset=UTF-8";

    protected $baseUrl;
    protected $requestHandler;
    protected $connectionTimeout = 2000;
    protected $readTimeout = 8000;
    protected $curlHandle;
    protected $logger;
    private $connected;
    private $requestHeaders = array();
    private $boundary;

    public function __construct($baseUrl, $requestHandler, $enableConsoleLog = TRUE) {
        $this->baseUrl = $baseUrl;
        $this->logger = new ConsoleLogger($enableConsoleLog);
        $this->curlHandle = curl_init();
        if ($requestHandler instanceof RequestHandler) {
            $this->requestHandler = $requestHandler;
        }
    }

    /**
     * This is the method that drives each request. It implements the request
     * lifecycle defined as open, prepare, write, read. Each of these methods in
     * turn delegates to the {@link RequestHandler} associated with this client.
     * @param string $path Whole or partial URL string, will be appended to baseUrl
     * @param string $contentType MIME type of the request
     * @param string $accept Response MIME
     * @return HttpResponse Response object
     */
    public function executeHttpRequest($path, $contentType, $accept) {
        try {
            // Not yet connected
            $this->connected = FALSE;
            $this->openConnection($path);

            // Assume connection sucessful
            $this->connected = TRUE;
            $this->prepareConnection($contentType, $accept);
            $this->appendRequestHeaders($this->curlHandle);

            // let us fire the http request
            $response = $this->writeToStream($this->curlHandle);
            return new HttpResponse($this->curlHandle, $response);
        } catch (Exception $ex) {
            print 'ERROR ' . $ex->getMessage();
        }
        return NULL;
    }

    /**
     * This is the method that drives each request. It implements the request
     * lifecycle defined as open, prepare, write, read. Each of these methods in
     * turn delegates to the {@link RequestHandler} associated with this client.
     * @param HttpRequest $httpRequest
     * @return HttpResponse
     */
    public function exec($httpRequest) {
        $httpResponse = NULL;
        try {
            if ($httpRequest != null && $httpRequest instanceof HttpRequest) {
                // Not yet connected
                $this->connected = FALSE;
                $this->openConnection($httpRequest->getPath());

                // The request Url is valid so we can reuse it here to grab the request headers
                // using the get_headers routine
                $requestUrl = $this->baseUrl . $httpRequest->getPath();

                // Assume connection sucessful
                $this->connected = TRUE;
                $this->prepareConnection($httpRequest->getContentType(), $httpRequest->getAccept());
                $this->appendRequestHeaders($this->curlHandle);

                // log request
                if ($this->logger->isLoggingEnabled()) {
                    $requestHeaders = get_headers($requestUrl, 1);
                    $this->logger->logRequest($httpRequest, $requestHeaders);
                }

                // let us fire the http request
                $response = $this->writeToStream($this->curlHandle);

                // check whether there are no errors that could trigger a CurlException
                if ($response === FALSE) {
                    $this->onError($requestUrl, curl_error($this->curlHandle));
                } else {
                    $httpResponse = new HttpResponse($this->curlHandle, $response);
                }
            }
        } catch (Exception $ex) {
            print "Error " . $ex->getTrace();
        }

        // Log the Http Response
        if ($httpResponse != NULL && $this->logger->isLoggingEnabled()) {
            $this->logger->logResponse($httpResponse);
        }
        return $httpResponse;
    }

    /**
     * Post files to a remote server.
     * @param string $path The path to the resource
     * @param string $accept The server expected response mime type
     * @param array $form additional form data to send along the file
     * @param array $files files to upload.
     * @return HttpResponse
     */
    public function postFiles($path, $accept, array $form = array(), array $files = array()) {
        try {
            // Not yet connected
            $this->connected = FALSE;
            $this->openConnection($path);

            // The request Url is valid so we can reuse it here to grab the request headers
            // using the get_headers routine
            $requestUrl = $this->baseUrl . $path;

            // Assume connection sucessful
            $this->connected = TRUE;
            // Use a new connection instead of a cached connection
            curl_setopt($this->curlHandle, CURLOPT_FRESH_CONNECT, TRUE);

            // Connection timeout
            curl_setopt($this->curlHandle, CURLOPT_CONNECTTIMEOUT_MS, $this->connectionTimeout);
            curl_setopt($this->curlHandle, CURLOPT_TIMEOUT_MS, $this->readTimeout);
            $this->appendRequestHeaders($this->curlHandle);
            $body = $this->buildCurlMulipartBody($accept, $form, $files);
            curl_setopt($this->curlHandle, CURLOPT_HEADER, FALSE);
            curl_setopt($this->curlHandle, CURLINFO_HEADER_OUT, true);
            curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, TRUE);

            curl_setopt_array($this->curlHandle, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    "Accept: $accept",
                    "Accept-Charset : UTF-8",
                    "Expect: 100-continue",
                    "Content-Type: multipart/form-data; boundary={$this->boundary}", // change Content-Type
                ),
            ));

            // log request
            if ($this->logger->isLoggingEnabled()) {
                $requestHeaders = get_headers($requestUrl, 1);
                $this->logger->logMultipartRequest($requestHeaders, $accept, "POST");
            }

            // let us fire the http request
            $response = $this->writeToStream($this->curlHandle);

            // check whether there are no errors that could trigger a CurlException
            if ($response === FALSE) {
                $this->onError($requestUrl, curl_error($this->curlHandle));
            } else {
                $httpResponse = new HttpResponse($this->curlHandle, $response);
            }
        } catch (Exception $ex) {
            print "Error " . $ex->getTrace();
        }

        // Log the Http Response
        if ($httpResponse != NULL && $this->logger->isLoggingEnabled()) {
            $this->logger->logResponse($httpResponse);
        }
        return $httpResponse;
    }

    /**
     * Validates a URL and opens a connection. This does not actually connect to
     * a server, but rather opens it on the client only to allow writing to begin.
     * Delegates the open operation to the {@link RequestHandler}.
     * @param string $path Appended to this client's baseUrl
     * @return cURL Handle or FALSE
     * @throws Exception
     */
    protected function openConnection($path) {
        $requestUrl = $this->baseUrl . $path;
        // Variable holding the validation status
        $ok = FALSE;

        // First and foremost let us validate the url and the path
        if (strpos($requestUrl, '?') === FALSE) {
            $ok = filter_var($requestUrl, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED & FILTER_FLAG_PATH_REQUIRED);
        } else {
            $ok = filter_var($requestUrl, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED & FILTER_FLAG_QUERY_REQUIRED);
        }

        if ($ok) {
            // Initialize the curl init
            $this->requestHandler->openConnection($this->curlHandle, $requestUrl);
        } else {
            throw new Exception($requestUrl . "  is not a valid URL ");
        }
    }

    /**
     * Prepares the curl session. Delegates the prepare operation to the {@link RequestHandler}.
     * @param cURL Handle $curlHandle
     * @param string $contentType
     * @param string $accept
     */
    protected function prepareConnection($contentType, $accept) {
        // Use a new connection instead of a cached connection
        curl_setopt($this->curlHandle, CURLOPT_FRESH_CONNECT, TRUE);

        // Connection timeout
        curl_setopt($this->curlHandle, CURLOPT_CONNECTTIMEOUT_MS, $this->connectionTimeout);
        curl_setopt($this->curlHandle, CURLOPT_TIMEOUT_MS, $this->readTimeout);

        // Pass it to the RequestHandler
        $this->requestHandler->prepareConnection($this->curlHandle, $contentType, $accept);
    }

    /**
     * This method wraps the call to fireHttpRequest.
     * @param HttpRequest $httpRequest
     * @return HttpResponse Response object
     */
    public function execute($httpRequest) {
        $httpResponse = NULL;

        if ($httpRequest instanceof HttpRequest) {
            // Uncomment this line if you do not want console log even if logging is enabled.
            //$httpResponse = $this->executeHttpRequest($httpRequest->getPath(), $httpRequest->getContentType(), $httpRequest->getAccept());
            $httpResponse = $this->exec($httpRequest);
        }
        return $httpResponse;
    }

    /**
     * Adds to the headers that will be sent with each request from this client
     * instance.
     * @param string $header
     * @param string $value
     * @return \AbstractHttpClient
     */
    public function addHeader($header, $value) {
        $this->requestHeaders[] = "$header : $value";
        return $this;
    }

    /**
     * Clears all request headers that have been added using
     * {@link #addHeader(String, String)}. This method has no effect on headers
     * which result from request properties set by this class or its associated
     * {@link RequestHandler} when preparing the curl session
     */
    public function clearHeader() {
        $this->requestHeaders = array();
    }

    /**
     * Append all headers added with {@link #addHeader(String, String)} to the
     * request.
     * @param mixed $curlHandle
     */
    private function appendRequestHeaders($curlHandle) {
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $this->requestHeaders);
    }

    /**
     *  Writes the request to the server. Delegates I/O to the
     * {@link RequestHandler}.
     * @param cURL Handle $curHandle
     * @return type
     */
    protected function writeToStream($curlHandle) {
        return $this->requestHandler->writeToStream($curlHandle);
    }

    /**
     * Sets the connection timeout in ms. This is the amount of time that
     * will wait to successfully connect to the remote
     * server. The read timeout begins once connection has been established.
     * @param int $connectionTimeout
     */
    public function setConnectionTimeout($connectionTimeout) {
        $this->connectionTimeout = $connectionTimeout;
    }

    /**
     * Sets the read timeout in ms, which begins after connection has been made.
     * For large amounts of data expected, bump this up to make sure you allow
     * adequate time to receive it.
     * @param int $readTimeout
     */
    public function setReadTimeout($readTimeout) {
        $this->readTimeout = $readTimeout;
    }

    /**
     * Execute a HEAD request and return the response.
     * @param string $path the Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The HEAD Payload
     * @return HttpResponse response object
     */
    public function head($path, array $params = NULL) {
        return $this->execute(new HttpHead($this->curlHandle, $path, "application/json", $params));
    }

    /**
     * Execute a HEAD request and return the response.
     * @param string $path the Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The HEAD Payload
     * @return HttpResponse response object
     */
    public function sendHead($path, $accept = "application/json", array $params = NULL) {
        return $this->execute(new HttpHead($this->curlHandle, $path, $accept, $params));
    }

    /**
     * Execute a GET request and return the response.
     * @param string $path the Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The query string 
     * @return HttpResponse response object
     */
    public function sendGet($path, $accept = "application/json", array $params = NULL) {
        return $this->execute(new HttpGet($this->curlHandle, $path, $accept, $params));
    }

    /**
     * Execute a GET request with query string and return the response.
     * @param string $path the Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The query string 
     * @return HttpResponse response object
     */
    public function get($path, array $params = NULL) {
        return $this->execute(new HttpGet($this->curlHandle, $path, "application/json", $params));
    }

    /**
     * Execute a POST request and return the response.
     * @param string $path The Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The POST payload
     * @return HttpResponse response object
     */
    public function post($path, array $params = NULL) {
        return $this->execute(new HttpPost($this->curlHandle, $path, "application/json", $params));
    }

    /**
     * Execute a POST request and return the response.
     * @param string $path The Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The POST payload
     * @return HttpResponse response object
     */
    public function sendPost($path, $accept = "application/json", array $params = NULL) {
        return $this->execute(new HttpPost($this->curlHandle, $path, $accept, $params));
    }

    /**
     * Execute a PUT request and return the response.
     * @param string $path The Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The POST payload
     * @return HttpResponse response object
     */
    public function put($path, array $params = NULL) {
        return $this->execute(new HttpPut($this->curlHandle, $path, "application/json", $params));
    }

    /**
     * Execute a PUT request and return the response.
     * @param string $path The Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The PUT payload
     * @return HttpResponse response object
     */
    public function sendPut($path, $accept = "application/json", array $params = NULL) {
        return $this->execute(new HttpPut($this->curlHandle, $path, $accept, $params));
    }

    /**
     * Execute a DELETE request and return the response.
     * @param string $path 
     * @param string $accept
     * @param array $params
     * @return HttpResponse response object
     */
    public function delete($path, array $params = NULL) {
        return $this->execute(new HttpDelete($this->curlHandle, $path, "application/json", $params));
    }

    /**
     * Execute a DELETE request and return the response.
     * @param string $path The Path to the resource
     * @param string $accept The Response MIME
     * @param array $params The DELETE payload
     * @return HttpResponse response object
     */
    public function sendDelete($path, $accept = "application/json", array $params = NULL) {
        return $this->execute(new HttpDelete($this->curlHandle, $path, $accept, $params));
    }

    /**
     * Sets Basic Authorization header
     * @param string $userName
     * @param string $password
     */
    public function setBasicAuth($userName, $password) {
        curl_setopt($this->curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->curlHandle, CURLOPT_USERPWD, "$userName:$password");
    }

    /**
     * Sets the OAuth Authorization header Bearer Token
     * @param string $token
     */
    public function setOAuth2BearerToken($token) {
        curl_setopt($this->curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        $this->addHeader("Authorization", "Bearer " . $token);
    }

    /**
     * Sets the logger to be used for each request.
     * @param RequestLogger $logger
     * @throws Exception
     */
    public function setLogger($logger) {
        if ($logger instanceof RequestLogger) {
            $this->logger = $logger;
        } else {
            throw new Exception("logger must implement RequestLogger");
        }
    }

    /**
     * Determines whether an exception was due to a timeout.
     * @return boolean true or false
     */
    protected function isTimeoutException() {
        // Get the last Error number
        $errno = curl_errno($this->curlHandle);
        if ($errno === CURLE_OPERATION_TIMEOUTED) {
            if ($this->logger->isLoggingEnabled()) {
                $this->logger->log("cURL error ($errno): \n Operation timeout. "
                        . "The specified time-out period CT={$this->connectionTimeout}, RT={$this->readTimeout} was reached.");
            }
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Determines whether an exception was due to connection.
     * @param string $host hostname
     * @return boolean true or false
     */
    protected function isConnectionException($host) {
        // Get the last Error number
        $errno = curl_errno($this->curlHandle);
        if ($errno === CURLE_COULDNT_CONNECT) {
            if ($this->logger->isLoggingEnabled()) {
                $this->logger->log("cURL error ($errno): \n Connection Exception. "
                        . "Fail to connect to Host:{$host}.");
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Determines whether an exception was due to host or proxy resolution.
     * @param string $host hostname or proxy
     * @return boolean true or false
     */
    protected function isHostResolutionException($host) {
        // Get the last Error number
        $errno = curl_errno($this->curlHandle);
        if ($errno === CURLE_COULDNT_RESOLVE_HOST || $errno === CURLE_COULDNT_RESOLVE_PROXY) {
            if ($this->logger->isLoggingEnabled()) {
                $this->logger->log("cURL error ($errno): \n Resolution Exception. "
                        . "Fail to resolve Host or Proxy:{$host}.");
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Determines whether an exception was due to session initialization failure.
     * @return boolean true or false
     */
    protected function isInitException() {
        // Get the last Error number
        $errno = curl_errno($this->curlHandle);
        if ($errno === CURLE_FAILED_INIT) {
            if ($this->logger->isLoggingEnabled()) {
                $this->logger->log("cURL error ($errno): \n Php Internal Exception. "
                        . "Curl session failed to initialize.");
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Handles the various possible expected errors when the curl_exec returns false 
     * Other errors are thrown by the executor.
     * @param string $host hostname or proxy
     * @param mixed $others Other errors
     * @throws Exception
     */
    protected function onError($host = NULL, $others = NULL) {
        // let us check the various possible exception
        if ($this->isConnectionException($host)) {
            throw new Exception("Unable to connect to {$host}", E_ERROR);
        } elseif ($this->isHostResolutionException($host)) {
            throw new Exception("Unable to resolve {$host}", E_ERROR);
        } elseif ($this->isInitException()) {
            trigger_error("Internal Error", E_ERROR);
        } elseif ($this->isTimeoutException()) {
            throw new Exception("Operation timed out", E_ERROR);
        } else {
            trigger_error("Unexpected Error:  {$others}", E_ERROR);
        }
    }

    /**
     * This function helps build multipart body for file upload.
     * @staticvar array $disallow
     * @param string $accept the expected result mime type from server
     * @param array $assoc
     * @param array $files
     * @return string The paylod body
     */
    protected function buildCurlMulipartBody($accept, array $assoc = array(), array $files = array()) {
        // invalid characters for "name" and "filename"
        static $disallow = array("\0", "\"", "\r", "\n");

        // build normal parameters
        foreach ($assoc as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                "",
                filter_var($v),
            ));
        }

        // build file parameters
        foreach ($files as $k => $v) {
            switch (true) {
                case false === $v = realpath(filter_var($v)):
                case!is_file($v):
                case!is_readable($v):
                    continue; // or return false, throw new InvalidArgumentException
            }
            $data = file_get_contents($v);

            // Let us get the file details
            $details = pathinfo($v);
            $fileName = $details["basename"];
            $fileName = str_replace($disallow, "_", $fileName);

            // Let us get the file mime type
            $fileContentType = FileExtensionMimeTypeMapping::getMimeType($fileName);

            // fieldName
            $k = str_replace($disallow, "_", $k);

            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$fileName}\"",
                "Content-Type: {$fileContentType}",
                "",
                $data,
            ));
        }

        // generate safe boundary 
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));

        $this->boundary = $boundary;

        // add boundary for each parameters
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });

        // add final boundary
        $body[] = "--{$boundary}--";
        $body[] = "";

        return implode("\r\n", $body);
    }

}
