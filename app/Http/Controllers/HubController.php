<?php
namespace App\Http\Controllers;
use App\Service;
use App\Http\Requests;
use \App\Helpers\Migration;
use Illuminate\Http\Request;
use Devless\SDK\SDK;
use App\Helpers\DevlessHelper as DLH;
use App\Http\Controllers\Controller;
class HubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sdk = new SDK("http://devless.herokuapp.com","10fa22f7466bafdad86dbde4cf451027");
        $data = $sdk->getData('DV_STORE', 'service');
        
        if($data['status_code'] == 625) {
            $services = $data['payload']['results'];
            $properties = $data['payload']['properties'];
           
        } else {
            DLH::flash("Could not connect to the store :(", 'error');
        }
        return view('hub.index', compact('services','properties'));
        
    }
    
    public function get_service($url, $serviceName)
    {
        $service = file_get_contents($url);
        $status = file_put_contents(storage_path().'/'.$serviceName, $service);

        if ($status) {
            $status = Migration::import_service($serviceName);
            return ($status)? "ok" : "false";
        } else {
            return false;
        }
    }
}
