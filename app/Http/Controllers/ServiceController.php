<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Service;
use Illuminate\Http\Request;
use App\Service as serviceModel;
use App\Helpers\Helper;
use App\Http\Controllers\ScriptController as script ;
use App\Http\Controllers\DbController as Db;

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
	{
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
	 *Refer request to the right service 
	 * @param string  $service  service to be accessed
         * @param string $resource resource to be accessed
	 * @return Response
	 */
        public function resource(Request $request, $service, $resource)
        {  
            $resource = strtolower($resource);$service = strtolower($service);
            $method = $request->method();
            #check method type and get payload accordingly 
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
                    
                 
            //$resource
            return $this->assign_to_service($service, $resource, $method, 
                    $parameters);
        }
        
       
        
        /**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $resource
         * @param array $method http verb
	 * @return Response
	 */
        public function assign_to_service($service, $resource, $method,
                $parameters)
        {
          
            if($current_service = serviceModel::where('name', $service)->
                    where('active',1)->first())
                     {
                        
                        
                    if($resource == 'db'){
                     
                            $payload = [
                            'id'=>$current_service->id,    
                            'db_definition' =>$current_service->db_definition, 
                            'pre_set' => $current_service->pre_set,
                            'post_set' => $current_service->post_set,
                            'calls' =>  $current_service->calls,
                            'method' => $method,
                            'params' => $parameters,   
                            
                                                         ];
                            $db = new Db(); 
                            $db->access_db($resource,$payload);
                            
                              
                    } 
                    else if($resource == 'script')
                          {
                             
                        
                            $payload = [
                            'id'=>$current_service->id,
                            'db_definition' =>$current_service->db_definition,     
                            'script' =>  $current_service->script, 
                            'pre_set' => $current_service->pre_set,
                            'post_set' => $current_service->post_set,
                            'calls' =>  $current_service->calls,
                            'method' => $method,
                             'params'=>$parameters,   
                               
                                                        ];
                        
                            $script = new script;
                            $script->run_script($resource,$payload);
                          }
                          else if($resource == 'schema')
                          {
                             
                        
                            $payload = [
                            'id'=>$current_service->id,  
                            'db_definition' =>$current_service->db_definition,     
                            'script' =>  $current_service->script, 
                            'pre_set' => $current_service->pre_set,
                            'post_set' => $current_service->post_set,
                            'calls' =>  $current_service->calls,  
                            'method' => $method,
                            'params'=>$parameters,   
                              
                                                        ];
                            $db = new Db();
                            $db->create_schema($resource, $payload);
                          }
                          else
                          {
                                Helper::interrupt(605);
                          }
            
                    
            }
            else
            {
                
                Helper::interrupt(604);
            }       
            
          
            //check for pre and post 
            
        }
}
