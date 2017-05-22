<?php

namespace App\Http\Controllers;

use App\Service;
use App\Helpers\Migration;
use Illuminate\Http\Request;
use App\Helpers\DevlessHelper as DLH;


class HubController extends Controller
{
    private $url = 'http://devless.herokuapp.com';
    private $token = '10fa22f7466bafdad86dbde4cf451027';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = [];
        $majorVersion = explode('.', config('devless')['version'])[0];
        $services = json_decode(file_get_contents('https://raw.githubusercontent.com/DevlessTeam/service-hub/master/services-v'.$majorVersion.'.json'), true);

        return view('hub.index', compact('services'));
    }

    public function get_service(Request $request)
    {
        $url = $request['url'];
        $parsed_url = parse_url($url);
        $paths = explode('/', $parsed_url['path']);
        $service_name = end($paths);
        $service_name_only = explode('.', $service_name)[0];
        DLH::instance_log($this->url, $this->token, 'Downloaded'.$service_name_only);
        $service = file_get_contents($url);
        $status = file_put_contents(storage_path().'/'.$service_name, $service);

        if ($status) {
            $status = Migration::import_service($service_name);
            $payload['install'] = '__onImport';
            $payload['serviceName'] = $service_name_only;
            DLH::execOnServiceStar($payload);

            return ($status) ? ['status' => 'true'] : ['status' => 'false'];
        } else {
            return ['status' => 'false'];
        }
    }
}
