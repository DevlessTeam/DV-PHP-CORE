<?php

namespace App\Http\Controllers;

use Validator;
use App\Service;
use App\Helpers\Helper;
use App\Helpers\DataStore;
use Illuminate\Http\Request;
use Devless\Schema\SchemaEdit;
use Devless\Schema\DbHandler as Db;
use App\Helpers\DevlessHelper as DLH;
use App\Helpers\Response as Response;
use Devless\Script\ScriptHandler as script;
use App\Http\Controllers\ViewController as DvViews;

class ServiceController extends Controller
{
    use \App\service\service_activity;
    use \App\service\service_assignment;
    use \App\service\service_downloader;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->paginate(10);
        $menuName = 'all_services';
        return view('services.index', compact('services', 'menuName'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $menuName = 'all_services';
        return view('services.create', compact('menuName'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $service = new Service();
        $scriptHandler = new script();
        $service_name_from_form = $request->input('name');
        $service_name_from_form = preg_replace('/\s*/', '', $service_name_from_form);
        $service_name_from_form = str_replace('-', '_', $service_name_from_form);
        $service_name = $service_name_from_form;
        $is_keyword = $this->is_service_name_php_keyword($service_name);

        $validator = Validator::make(
            ['Service Name' => $service_name, 'Devless' => 'devless'],
            [
                'Service Name' => 'required|unique:services,name|min:3|max:15|different:Devless',
            ]
        );

        if ($validator->fails() || $is_keyword) {
            $errors = $validator->messages();
            $message = ($validator->fails())?"Sorry but service could not be created":"Sorry but $service_name is a keyword and can't be used as a `service name`";
            DLH::flash($message, 'error');

            return redirect()->route('services.create')->with('errors', $errors)->withInput();
        }
        $service->name = $service_name;
        $serviceFields = ['description', 'username', 'password',
                'database', 'password', 'database', 'hostname', 'driver', 'port', ];
        foreach ($serviceFields as $serviceField) {
            $service->{$serviceField} = $request->input($serviceField);
            $connection[$serviceField] = $service->{$serviceField};
        }
        $service->script_init_vars = '$rules = null;';
        $service->resource_access_right =
            '{"query":1,"create":1,"update":1,"delete":1,"schema":0,"script":0, "view":0}';
        $service->active = 1;
        $service->raw_script = DLH::script_template();
        $compiled_script  = $scriptHandler->compile_script(DLH::script_template());
        $service->script = $compiled_script['script'];        
        $db = new Db();
        if ( !$db->check_db_connection($connection) ) {
            DLH::flash('Sorry connection could not be made to Database', 'error');
        } else {
            //create initial views for service
            $views = new DvViews();
            $type = 'init';
            $payload['serviceName'] = $service_name;
            $views_created = $views->create_views($service_name, $type);
            ($service->save() && $views_created)
                ?
                DLH::flash('Service created successfully', 'success') :
                DLH::flash('Service could not be created', 'error');
        }

        return $this->edit($service->id);
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
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
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $table_meta = \App\TableMeta::where('service_id', $id)->get();
        $count = 0;
        foreach ($table_meta as $each_table_meta) {
            $table_meta[$count] = (json_decode($each_table_meta->schema, true));
            ++$count;
        }
        $menuName = 'all_services';
        return view('services.edit', compact('service', 'table_meta', 'id', 'menuName'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ($service = Service::findOrFail($id)) {
            if ($request->input('call_type') == 'solo') {
                $script = $request->input('script');
                $service_name = $service->name;
                $db = new DataStore();
                $scriptHandler = new script();
                $service->raw_script = $script;
                $compiled_script  = $scriptHandler->compile_script($script);
                if(!$compiled_script['successful']) {
                    return Response::respond(1001, $compiled_script['error_message']);
                }
                $service->script_init_vars = $compiled_script['var_init'];
                $service->script = $compiled_script['script'];
                $service->save();
                return Response::respond(626);    
                
            }
            $connection = [];
            $serviceFields = ['description', 'username', 'password',
                'database', 'password', 'database', 'hostname', 'driver', 'port', 'active', ];
            foreach ($serviceFields as $serviceField) {
                $service->{$serviceField} = $request->input($serviceField);
                $connection[$serviceField] = $service->{$serviceField};
            }
            $db = new Db();
            if (!$db->check_db_connection($connection)) {
                DLH::flash('Sorry connection could not be made to Database', 'error');
            } else {
                ($service->save()) ? DLH::flash('Service updated successfully', 'success') :
                    DLH::flash('Changes did not take effect', 'error');
            }
        }

        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
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
            DLH::flash('Service deleted successfully '.$execOutput, 'success');
        } else {
            DLH::flash('Service could not be deleted', 'error');
        }
        
        return redirect()->route('services.index');
    }

    /**
     * All api calls go through here.
     *
     * @param array|Request $request  request params
     * @param string        $service  service to be accessed
     * @param string        $resource resource to be accessed
     *
     * @return Response
     *
     * @internal param $newServiceElements
     */
    public function service(Request $request, $service, $resource)
    {
        $this->_devlessCheckHeaders($request);
        $serviceOutput = $this->resource($request, $service, $resource);
        return response()->json($serviceOutput, 200, [], JSON_NUMERIC_CHECK);
    }
    /**
     * Refer request to the right service and resource.
     *
     * @param array        $request         request params
     * @param $service_name
     * @param string       $resource        resource to be accessed
     * @param bool         $internal_access
     *
     * @return Response
     *
     * @internal param string $service service to be accessed
     */
    public function resource($request, $service_name, $resource, $internal_access = false)
    {
        $resource = strtolower($resource);
        ($internal_access == true) ? $method = $request['method'] :
            $method = $request->method();
        $method = strtoupper($method);
        // check method type and get payload accordingly
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
     * check if Devless headers are set.
     *
     * @param type $request
     */
    private function _devlessCheckHeaders($request)
    {
        $is_token_set = ($request->header('Devless-token') == $request['devless_token']) ? true : false;
        $is_admin = Helper::is_admin_login();
        (!($is_token_set || $is_admin)) ? Helper::interrupt(631) : '';
    }

    public function editTable(Request $request, $action, $service, $table, $params)
    {
        $se = new SchemaEdit();
        $params = explode('-:-', $params);
        $updateTable = function () use ($se, $service, $table, $params) {
            if (!sizeof($params) == 2) {
                return ['status' => 'failed'];
            }
            $se->updateTableName($service, $table, $params[0]);
            $se->updateTableDesc($service, $params[0], $params[1]);

            return '{"status":"ok"}';
        };

        $addField = function () use ($se, $service, $table, $params) {
            if (!sizeof($params) == 2) {
                return ['status' => 'failed'];
            }
            $se->addField($service, $table, $params[0], strtolower($params[1]));

            return ['status' => 'ok'];
        };

        $updateFieldName = function () use ($se, $service, $table, $params) {
            if (!sizeof($params) == 2) {
                return ['status' => 'ok'];
            }
            $se->updateFieldName($service, $table, $params[0], $params[1]);

            return ['status' => 'ok'];
        };

        $deleteField = function () use ($se, $service, $table, $params) {
            if (!sizeof($params) == 1) {
                return ['status' => 'failed'];
            }
            $se->delField($service, $table, $params[0]);

            return ['status' => 'ok'];
        };

        return $$action();
    }
}
