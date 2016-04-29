<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Service;
use Illuminate\Http\Request;
use App\Service as serviceModel;
use App\Helpers\Helper;
use App\Http\Controllers\ScriptController as script ;
use App\Http\Controllers\schemaController as schema;

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
        
        //
        public function resource(Request $request, $resource)
        {   
            $method = $request->method();
            parse_str($request->getQueryString(), $parameters);
            $get_params = $parameters;
            //$resource
            return $this->assign_to_service($resource,$method,$get_params);
        }
        
       
        
        /**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $resource
         * @param array $method http verb
	 * @return Response
	 */
        public function assign_to_service($resource, $method, $param)
        {
  
            if($current_service = serviceModel::where('name', $resource)->
                    where('active',1)->first())
                     {
                
                    if($current_service->type == 0){
                     
                            $payload = [
                            'db_definition' =>$current_service->db_definition, 
                            'pre_set' => $current_service->pre_set,
                            'post_set' => $current_service->post_set,
                            'calls' =>  $current_service->calls,
                            'method' => $method,
                            'params' => $param   
                                                         ];
                            $schema = new schema(); 
                            $schema->access_db($resource,$payload);
                            
                              
                    } 
                    else if($current_service->type == 1)
                          {
                             
                        
                            $payload = [
                            'script' =>  $current_service->script, 
                            'pre_set' => $current_service->pre_set,
                            'post_set' => $current_service->post_set,
                            'calls' =>  $current_service->calls,
                            'method' => $method,
                             'params'=>$param   
                                                        ];
                        
                            $script = new script;
                            $script->run_script($resource,$payload);
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
