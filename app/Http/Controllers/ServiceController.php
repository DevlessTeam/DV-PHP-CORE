<?php namespace App\Http\Controllers;

use Validator;
use App\Service;
use App\Helpers\Helper;
use App\Helpers\DataStore;
use Illuminate\Http\Request;
use App\Service as serviceModel;
use Devless\Schema\DbHandler as Db;
use App\Helpers\DevlessHelper as DLH;
use App\Helpers\Response as Response;
use Devless\Script\ScriptHandler as script;
use App\Http\Controllers\ViewController as DvViews;
use App\Http\Controllers\RpcController as Rpc;

class ServiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->paginate(10);
        return view('services.index', compact('services'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('services.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $service = new Service();
        $service_name_from_form = $request->input("name");
        $service_name_from_form = preg_replace('/\s*/', '', $service_name_from_form);
        $service_name_from_form = str_replace('-', '_', $service_name_from_form);
        $service_name = $service_name_from_form;
        $validator = Validator::make(

            ['Service Name'=>$service_name,'Devless'=>'devless'],
            [
                'Service Name'=>'required|unique:services,name|min:3|max:15|different:Devless',
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->messages();
            DLH::flash("Sorry but service could not be created", 'error');
            return redirect()->route('services.create')->with('errors', $errors)->withInput();
        }
        $service->name = $service_name;
        $service->description = $request->input("description");
        $service->username = $request->input("username");
        $service->password = $request->input('password');
        $service->database = $request->input('database');
        $service->hostname = $request->input('hostname');
        $service->script_init_vars = '$rules = null;';
        $service->driver = $request->input('driver');
        $service->resource_access_right =
            '{"query":0,"create":0,"update":0,"delete":0,"schema":0,"script":0, "view":0}';
        $service->active = 1;
        $service->script = 'use App\Helpers\Assert as Assert;
 $rules
 -> onQuery()
 -> onUpdate()
 -> onDelete()
 -> onCreate()
 ';
        $connection =
            [
                'username' => $service->username,
                'password' => $service->password,
                'database' => $service->database,
                'hostname' => $service->hostname,
                'driver'   => $service->driver,
            ];
        $db = new Db();
        if (!$db->check_db_connection($connection)) {
            DLH::flash("Sorry connection could not be made to Database", 'error');
        } else {
            //create initial views for service
            $views = new DvViews();
            $type = "init";
            $payload['serviceName'] = $service_name;
            $views_created = $views->create_views($service_name, $type);
            ($service->save() && $views_created )
                ?
                DLH::flash("Service created successfully", 'success'):
                DLH::flash("Service could not be created", 'error');
        }
        return $this->edit($service->id);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.show', compact('service'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $table_meta = \App\TableMeta::where('service_id', $id)->get();
        $count = 0;
        foreach ($table_meta as $each_table_meta) {
            $table_meta[$count]  = (json_decode($each_table_meta->schema, true));
            $count++;
        }
        return view('services.edit', compact('service', 'table_meta'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ($service = Service::findOrFail($id)) {
            if ($request->input('call_type') =='solo') {
                $script = $request->input('script');
                $service_name = $service->name;
                $db = new DataStore();
                $var_init = $this->var_init($script);
                $service->script_init_vars = $var_init;
                $service->script = $script;

                $service->save();
                return Response::respond(626);
            }
            $service->description = $request->input("description");
            $service->username = $request->input("username");
            $service->password = $request->input('password');
            $service->database = $request->input('database');
            $service->hostname = $request->input('hostname');
            $service->driver = $request->input('driver');
            $service->active = $request->input("active");
            $connection =
                [
                    'username' => $service->username,
                    'password' => $service->password,
                    'database' => $service->database,
                    'hostname' => $service->hostname,
                    'driver'   => $service->driver,
                ];
            $db = new Db();
            if (!$db->check_db_connection($connection)) {
                DLH::flash("Sorry connection could not be made to Database", 'error');
            } else {
                ($service->save())? DLH::flash("Service updated successfully", 'success'):
                    DLH::flash("Changes did not take effect", 'error');
            }
        }
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service_name = $service->name;
        $view_path = config('devless')['views_directory'];
        $assets_path = $view_path.$service_name;

        $table_meta = \App\TableMeta::where('service_id', $id)->orderBy('id', 'desc')->get();

        foreach ($table_meta as $meta) {
            $table_name = $meta->table_name;
            DLH::purge_table($service_name, $table_name);
        }
        $payload['serviceName'] = $service_name;
        $payload['delete'] = '__onDelete';
        $execOutput = DLH::execOnServiceStar($payload);
        if (DLH::deleteDirectory($assets_path) && $service->delete()) {
            DLH::flash("Service deleted successfully ".$execOutput, 'success');
        } else {
            DLH::flash("Service could not be deleted", 'error');
        }
        return redirect()->route('services.index');
    }
    /**
     * download service packages
     * @param $filename
     * @return
     * @internal param $request
     */
    public function download_service_package($filename)
    {
        $file_path = DLH::get_file($filename);
        if ($file_path) {
            // Send Download
            return \Response::download($file_path, $filename)->deleteFileAfterSend(true);
        } else {
            DLH::flash("could not download files");
        }
    }
    /**
     * All api calls go through here
     * @param array|Request $request request params
     * @param string $service service to be accessed
     * @param string $resource resource to be accessed
     * @return Response
     * @internal param $newServiceElements
     */
    public function service(Request $request, $service, $resource)
    {
        $this-> _devlessCheckHeaders($request);
        $serviceOutput = $this->resource($request, $service, $resource);
        return response($serviceOutput);
    }
    /**
     * Refer request to the right service and resource
     * @param array $request request params
     * @param $service_name
     * @param string $resource resource to be accessed
     * @param bool $internal_access
     * @return Response
     * @internal param string $service service to be accessed
     */
    public function resource($request, $service_name, $resource, $internal_access = false)
    {
        $resource = strtolower($resource);
        ($internal_access == true)? $method = $request['method'] :
            $method = $request->method();
        $method = strtoupper($method);
        #check method type and get payload accordingly
        if ($internal_access == true) {
            $parameters = $request['resource'];
        } else {
            $parameters = $this->get_params($method, $request);
        }
        return $this->assign_to_service(
            $service_name,
            $resource,
            $method,
            $parameters,
            $internal_access
        );
    }
    /**
     * assign request to a devless resource eg: db, view, script, schema, .
     *
     * @param $service_name
     * @param  string $resource
     * @param array $method http verb
     * @param null $parameters
     * @param bool $internal_access
     * @return Response
     * @internal param string $service name of service to be access
     * @internal param array $parameter contains all parameters passed from route
     * @internal param bool $internal_service true if service is being called internally
     */
    public function assign_to_service(
        $service_name,
        $resource,
        $method,
        $parameters = null,
        $internal_access = false
    ) {

        $current_service = $this->service_exist($service_name);
        if (!$current_service == false) {
            //check service access right
            $is_it_public = $current_service->public;
            $is_admin = Helper::is_admin_login();
            $accessed_internally = $internal_access;
            if ($is_it_public == 0 || $is_admin == true) {

                $resource_access_right =
                  $this->_get_resource_access_right($current_service, $accessed_internally);

                $payload =
                    [
                        'id'=> $current_service->id,
                        'service_name' =>$current_service->name,
                        'database' =>$current_service->database,
                        'driver' => $current_service->driver,
                        'hostname' => $current_service->hostname,
                        'username' => $current_service->username,
                        'password' => $current_service->password,
                        'script_init_vars' => $current_service->script_init_vars,
                        'calls' =>  $current_service->calls,
                        'resource_access_right' =>$resource_access_right,
                        'script' => $current_service->script,
                        'method' => $method,
                        'params' => $parameters,
                    ];
                // run script before assigning to method
                $newServiceElements = $this->before_assigning_service_action($resource, $payload);
                $resource = $newServiceElements['resource'];
                $payload = $newServiceElements['payload'];

                //keep names of resources in the singular
                switch ($resource) {
                    case 'db':
                        $db = new Db();
                        return $db->access_db($payload);
                        break;
                    case 'schema':
                        $db = new Db();
                        return $db->create_schema($payload);
                        break;
                    case 'view':
                        return $payload;
                    case 'rpc':
                        ($method != 'POST')? Helper::interrupt(639): true;
                        $rpc = new Rpc();
                        return $rpc->index($payload);
                    default:
                        Helper::interrupt(605);
                        break;
                }
            } else {
                Helper::interrupt(624);
            }
        }
    }
    /**
     *check if service exists
     *
     * @param string $service_name name of service
     * return array of service values
     */
    public function service_exist($service_name)
    {
        if ($current_service = serviceModel::where('name', $service_name)->
        where('active', 1)->first()) {
            return $current_service;
        } elseif (config('devless')['devless_service']->name == $service_name) {
            $current_service = config('devless')['devless_service'];
            return $current_service;
        } else {
            Helper::interrupt(604);
        }

    }
    /**
     * get parameters set in from request
     *
     * @param string $method reuquest method type
     * @param array $request request parameters
     * return array of parameters
     * @return array|mixed
     */
    public function get_params($method, $request)
    {
        if (in_array($method, ['POST','DELETE','PATCH'])) {
            $parameters = $request['resource'];
        } elseif ($method == 'GET') {
            $parameters = Helper::query_string();
        } else {
            Helper::interrupt(608, 'Request method '.$method.
                ' is not supported');
        }
        return $parameters;
    }
    /**
     * get and convert resource_access_right to array
     * @param object $service service payload
     * @return array resource access right
     */
    private function _get_resource_access_right($service, $master_access=false)
    {
        $mutate_resource_rights =
                function($rights) {return array_map(function($access_code)
                        { return $access_code ?: 1; }, $rights);};

        $resource_access_right = $service->resource_access_right;
        $resource_access_right = json_decode($resource_access_right, true);
        $resource_access_right = ($master_access)?
            $mutate_resource_rights($resource_access_right) : $resource_access_right;

        return $resource_access_right;
    }

    /**
     * check if Devless headers are set
     * @param type $request
     */
    private function _devlessCheckHeaders($request)
    {
        $is_token_set = ($request->header('Devless-token') == $request['devless_token'] )? true : false;
        $is_admin = Helper::is_admin_login();
        $state = ( $is_token_set || $is_admin )? true : false;
        if (!$state) {
            Helper::interrupt(631);
        }
    }
    /**
     * check user resource  action access right eg: query db or write to table
     * @param $access_type
     * @return bool
     * @internal param object $service service payload
     */
    public function check_resource_access_right_type($access_type)
    {

        $is_user_login = Helper::is_admin_login();
        if (! $is_user_login && $access_type == 0) {
            Helper::interrupt(627);
        } //private
        elseif ($access_type == 1) {
            return false;
        } //public
        elseif ($access_type == 2) {
            return true;
        }//authentication required
        return true;
    }
    /**
     * operations to execute before assigning action to resource
     * @param string $resource
     * @params array $payload
     * @return array
     */
    public function before_assigning_service_action($resource, $payload)
    {
        $output['resource'] = $resource;
        $output['payload'] = $payload;
        if ($resource != 'schema') {
            $script = new script;
            $output =  $script->run_script($resource, $payload);
        }
        return $output;
    }

    /**
     * create service views
     * @return string
     */
    public function service_views()
    {

            $folder_path = config('devless')['views_directory'];
            $db_name = \Config::get('database.connections.'.\Config::get('database.default').'.database');

            //get db name
            DLH::zip_folder($folder_path, 'download.zip');
            $mode = 0777;
            $zip = $folder_path.'/'.'download.zip';
            chmod($zip, $mode);
            copy($zip, public_path().'/download.zip');
            unlink($zip);
            return "created";

    }

    public function var_init($code)
    {
        $declarationString = '';
        $tokens = token_get_all('<?php '.$code);
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $start = 1;
                if ($token[0] == 312) {
                     $variable = substr($token[1], $start);
                     $declarationString .= "$$variable = null;";
                }
            }
        }
        return $declarationString;
    }
}
