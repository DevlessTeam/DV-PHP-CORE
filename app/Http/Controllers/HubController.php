<?php
namespace App\Http\Controllers;
use App\Service;
use App\Http\Requests;
use \App\Helpers\Migration;
use Illuminate\Http\Request;
use Devless\SDK\SDK;
use App\Helpers\DevlessHelper as DLH;
use App\Http\Controllers\Controller;
use App\Helpers\DataStore;
class HubController extends Controller
{
    private $url = "http://devless.herokuapp.com";
    private $token = "10fa22f7466bafdad86dbde4cf451027";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = [];
        $properties = [];
        $sdk = new SDK($this->url,$this->token);
        $data = $sdk->getData('DV_STORE', 'service');
        
        if(isset($data['status_code']) && $data['status_code'] == 625) {
            $services = $data['payload']['results'];
            $properties = $data['payload']['properties'];
           
        } else {
            DLH::flash("Could not connect to the store :(", 'error');
        }
        return view('hub.index', compact('services','properties'));
        
    }
    
    public function get_service(Request $request)
    {
       
        $url = $request['url'];
        $parsed_url = parse_url($url);
        $paths = explode('/', $parsed_url['path']);
        $service_name = end($paths);
        $service_name_only = explode('.', $service_name)[0];
        $service_exists = \DB::table('services')->where('name', $service_name_only)->first();
        DLH::instance_log($this->url, $this->token, 'Downloaded'.$service_name_only);
        if($service_exists) {
               return '{"status":"false"}';
        } 
       
        $service = file_get_contents($url);
        $status = file_put_contents(storage_path().'/'.$service_name, $service);
        
        if ($status) {
            $status = Migration::import_service($service_name);
            return ($status)? '{"status":"true"}' : '{"status":"false"}';
        } else {
            return '{"status":"false"}';
        }
    }
    
   
}
