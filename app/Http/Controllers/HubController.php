<?php

namespace App\Http\Controllers;

use App\Service;
use App\Helpers\Migration;
use Illuminate\Http\Request;
use App\Helpers\DevlessHelper as DLH;

class HubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = [];
        $majorVersion = explode('.', config('devless')['version'])[0];
        $services = json_decode(file_get_contents('https://raw.githubusercontent.com/DevlessTeam/service-hub/master/services-v'.$majorVersion.'.json'), true);
        $menuName = 'service_hub';
        return view('hub.index', compact('services', 'menuName'));
    }

    public function get_service(Request $request)
    {
        $main_url  = $request['url'];
        $urls = explode(',',$request['dep']);
        array_push($urls, $main_url);
        foreach( $urls as $url ){
            if(strlen($url)>0){
               $status = $this->install_service($url);           
            }
        }
        return ($status) ? ['status' => 'true'] : ['status' => 'false'];
    }

    public function install_service($url)
    {
        $parsed_url = parse_url($url);
        $paths = explode('/', $parsed_url['path']);
        $service_name = end($paths);
        $service_name_only = explode('.', $service_name)[0];
        $service = file_get_contents($url);
        $status = file_put_contents(storage_path().'/'.$service_name, $service);

        if ($status) {
            $status = Migration::import_service($service_name);
            $payload['install'] = '__onImport';
            $payload['serviceName'] = $service_name_only;
            DLH::execOnServiceStar($payload);

            return $status;
        } else {
            return false;
        }
    }
}
