<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Service;
use Illuminate\Http\Request;
use App\Service as serviceModel;
use App\Helpers\Helper;
use App\Http\Controllers\ScriptController as Script ;
use App\Http\Controllers\DbController as Db;
use App\Http\Controllers\ViewController as View;

class ServiceController extends Controller {

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
	{       //convert word inputs to lowercase
		$service = new Service();

		$service->name = $request->input("name");
        $service->description = $request->input("description");
        $service->type = $request->input("type");
        $service->db_definition = $request->input("db_definition");
        $service->script = $request->input("script");
        $service->pre_script = $request->input("pre_script");
        $service->post_script = $request->input("post_script");
        $service->pre_set = $request->input("pre_set");
        $service->post_set = $request->input("post_set");
        $service->active = $request->input("active");

		$service->save();

		return redirect()->route('services.index')->with('message', 'Item created successfully.');
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

		return view('services.edit', compact('service'));
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
                //convert name inputs to lowercase
		$service = Service::findOrFail($id);

		$service->name = $request->input("name");
                $service->description = $request->input("description");
                $service->type = $request->input("type");
                $service->db_definition = $request->input("db_definition");
                $service->script = $request->input("script");
                $service->pre_script = $request->input("pre_script");
                $service->post_script = $request->input("post_script");
                $service->pre_set = $request->input("pre_set");
                $service->post_set = $request->input("post_set");
                $service->active = $request->input("active");

		$service->save();

		return redirect()->route('services.index')->with('message', 'Item updated successfully.');
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
		$service->delete();

		return redirect()->route('services.index')->with('message', 
                        'Item deleted successfully.');
	}
        
        /**
        * all api calls go through here
        * @param array  $request request params 
        * @param string  $service  service to be accessed
        * @param string $resource resource to be accessed
        * @return Response
        */
        public function api(Request $request, $service, $resource)
        {
            $this->resource($request, $service, $resource);
        }
        
        /**
	 * Refer request to the right service and resource  
         * @param array  $request request params 
	 * @param string  $service  service to be accessed
         * @param string $resource resource to be accessed
	 * @return Response
	 */
        public function resource($request, $service, $resource, $internal_access=false)
        {  
            $resource = strtolower($resource);
            $service = strtolower($service);
            ($internal_access == true)? $method = $request['method'] :
            $method = $request->method();
            $method = strtoupper($method);
            #check method type and get payload accordingly 
            
            $parameters = $this->get_params($method, $request);
                    
                 
            //$resource
            return $this->assign_to_service($service, $resource, $method, 
                    $parameters);
        }
        
       
        
        /**
	 * assign request to a devless service .
	 *
         * @param string $service name of service to be access 
	 * @param  string  $resource
         * @param array $method http verb
         * @param array $parameter contains all parameters passed from route
	 * @return Response
	 */
        public function assign_to_service($service, $resource, $method,
                $parameters=null)
        {       
                $current_service = $this->service_exist($service);
                $payload = [
                    'id'=>$current_service->id,  
                    'service_name' =>$current_service->name,
                    'db_definition' =>$current_service->db_definition, 
                    'pre_set' => $current_service->pre_set,
                    'post_set' => $current_service->post_set,
                    'calls' =>  $current_service->calls,
                    'script' => $current_service->script,
                    'method' => $method,
                    'params' => $parameters, 
                ]; 
                //keep names of resources in the singular
                 switch ($resource)
                 {
                    case 'db':
                        
                        $db = new Db(); 
                            $db->access_db($resource,$payload);
                            break;    
                            
                    case 'script':
                        
                         $script = new script;
                            $script->run_script($resource,$payload);
                            break;
                            
                    case 'schema':
                        $db = new Db();
                            $db->create_schema($resource, $payload);
                            break;
                    
                    case 'views':
                        return $payload;
                        
                    default:
                        Helper::interrupt(605); 
                 }
                      
                 
            
                    
            }
                 
            
          
            
            /*
             * get parameters from request
             * 
             * @param string $service_name name of service 
             * return array of service values 
             */
            public function service_exist($service_name)
            {
                if($current_service = serviceModel::where('name', $service_name)->
                    where('active',1)->first())
                     {
                             return $current_service;
                     }
                     else
                     {
                         Helper::interrupt(604);
                     }
            }
            
            /*
             * get parameters from request
             * 
             * @param string $method reuquest method type 
             * @param array $request request parameters 
             * return array of parameters
             */
            public function get_params($method, $request)
            {
                    if(in_array($method,['POST','DELETE','PATCH']))
                {
                     $parameters = $request['resource'];

                 }
                 else if($method == 'GET')  
                {
                     $parameters = Helper::query_string();

                }
                else
                {
                    Helper::interrupt(608, 'Request method '.$method.
                            ' is not supported');        
                }
                return $parameters;
            }
        //check for pre and post 
}
