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

        $state = $this->create_service_from_request($request);
        return ($state[0])? $this->edit($state[1]->id) :
             redirect()->route('services.create')->with('errors', $state[1])->withInput();
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
                $service->raw_script = $script;
                $compiled_script  = (new script())->compile_script($script);
                if (!$compiled_script['successful']) {
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
        return response()->json($serviceOutput, 200, []);
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
            $parameters = $this->get_params($method, $request, $resource);
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
            if (!sizeof($params) == 3) {
                return ['status' => 'failed'];
            }
            $se->addField($service, $table, $params[0], strtolower($params[1]), $params[2], $params[3]=null);

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
