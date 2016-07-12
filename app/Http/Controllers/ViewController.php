<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Controllers\ServiceController as Service;

use \App\Helpers\Response as Response;
use App\Helpers\Helper as Helper;

class ViewController extends Controller
{
    public $MIME_LIST =
    [
        'js' => 'text/javascript',
        'css' => 'text/css',
        'default' => 'text/plain',
    ];
    public function access_views(Request $request, $service_name, $resource, $template)
    {
        $method = $request->method();
        $service = new Service();
        $payload = $service->assign_to_service($service_name, $resource, $method);
        $params = $service->get_params($method, $request);
        $payload['params'] = $params;
       
        $access_type = $payload['resource_access_right'];
        $access_state = $service->check_resource_access_right_type($access_type["view"]);
        $user_cred = Helper::get_authenticated_user_cred($access_state);
        
        return $this->_fetch_view($service_name, $template, $payload);
        
    }
 
    private function _fetch_view($service, $template, $payload)
    {
        
        return view('service_views.'.$service.'.'.$template)->with('payload', $payload);
        
    }
    
    public function static_files(Request $request)
    {
        
        $get_mime_type = $this->MIME_LIST;
        $asset_file_path = '../resources/views/'.$request->path();
        $asset_file_extension = pathinfo($asset_file_path, PATHINFO_EXTENSION);
        
        $using_asset_file_extension = $asset_file_extension;
        (isset($get_mime_type[$using_asset_file_extension]))? $file_mime =
                $get_mime_type[$using_asset_file_extension] :
                $file_mime = $get_mime_type['default'];
        
        if (file_exists($asset_file_path)) {
            $content = file_get_contents($asset_file_path);
             return response($content)->header('Content-Type', $file_mime);
        } else {
            return Response::respond(621);
        }
    }
    
    public function create_views($service_name, $type)
    {

        switch ($type) {
            case "init":
                $source_path = base_path().'/resources/views/welcome.blade.php';
                $destination_path = config('devless')['views_directory'].$service_name;
                
                if (mkdir($destination_path)) {
                    $is_saved = (copy($source_path, $destination_path.'/index.blade.php'))?true
                          : false;
                  
                    return $is_saved;
                } else {
                    return false;
                }
                
                
            default:
                return false;
        }
        
        
        
    }
    
    public function rename_view($old_service_name, $new_service_name)
    {
        $old_path = config('devless')['views_directory'].$old_service_name;
        $new_path = config('devless')['views_directory'].$new_service_name;
        
        return (rename($old_path, $new_path))? true : false;
        
        
    }
}
