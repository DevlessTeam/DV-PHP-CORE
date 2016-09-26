<?php

namespace App\Http\Controllers;

use App\Helpers\DevlessHelper;
use App\Helpers\Helper as Helper;
use App\Helpers\Response as Response;
use App\Http\Controllers\ServiceController as Service;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public $MIME_LIST =
    [
        'js'      => 'text/javascript',
        'css'     => 'text/css',
        'default' => 'text/plain',
    ];

    /**
     * Get views related to a service.
     *
     * @param Request $request
     * @param string  $service_name
     * @param string  $resource
     * @param string  $template
     *
     * @return object
     */
    public function access_views(Request $request, $service_name, $resource, $template)
    {
        $method = $request->method();
        $service = new Service();
        $payload = $service->assign_to_service($service_name, $resource, $method);
        $payload['params'] = $service->get_params($method, $request);

        $access_type = $payload['resource_access_right'];
        $access_state = $service->check_resource_access_right_type($access_type['view']);
        Helper::get_authenticated_user_cred($access_state);

        return $this->_fetch_view($service_name, $template, $payload);
    }

    /**
     * Get view from service_view directory.
     *
     * @param string $service
     * @param string $template
     * @param string $payload
     *
     * @return object
     */
    private function _fetch_view($service, $template, $payload)
    {
        return view('service_views.'.$service.'.'.$template)->with('payload', $payload);
    }

    /**
     * Get static files for service_views.
     *
     * @param Request $request
     *
     * @return string
     */
    public function static_files(Request $request)
    {
        $url = $request->path();
        $viewsDirectory = config('devless')['views_directory'];

        $splitUrl = $sub = explode('/', $url, 3);
        $route = $splitUrl[0];
        $serviceName = (isset($splitUrl[1])) ? $splitUrl[1] : '';
        $assetsSubPath = (isset($splitUrl[2])) ? $splitUrl[2] : '';

        $asset_file_path =
                \App\Helpers\DevlessHelper::assetsDirectory($serviceName, $assetsSubPath);

        $asset_file_extension = pathinfo($asset_file_path, PATHINFO_EXTENSION);

        $get_mime_type = $this->MIME_LIST;

        $using_asset_file_extension = $asset_file_extension;
        (isset($get_mime_type[$using_asset_file_extension])) ? $file_mime =
                $get_mime_type[$using_asset_file_extension] :
                $file_mime = $get_mime_type['default'];

        if (file_exists($asset_file_path)) {
            $content = file_get_contents($asset_file_path);

            return response($content)->header('Content-Type', $file_mime);
        } else {
            return Response::respond(621, null, ['filePath' => $asset_file_path]);
        }
    }

    /**
     * create initial view files for new services.
     *
     * @param string $service_name
     * @param string $type
     *
     * @return bool
     */
    public function create_views($service_name, $type)
    {
        switch ($type) {
            case 'init':
                $source_path =  base_path().'/resources/views/service_template';
                $destination_path = config('devless')['views_directory'].$service_name;
                
                if(file_exists($destination_path)){DevlessHelper::rmdir_recursive($destination_path);}
                
                if (mkdir($destination_path)) {
                    $copied_to_destination =  DevlessHelper::recurse_copy($source_path, $destination_path);

                    $payload['serviceName'] = $service_name;
                    $exec_success = DevlessHelper::execOnViewsCreation($payload);

                    return ($exec_success && $copied_to_destination);
                } else {
                    return false;
                }
            default:
                return false;
        }
    }

    /**
     * Rename view directory name.
     *
     * @param string $old_service_name
     * @param string $new_service_name
     *
     * @return bool
     */
    public function rename_view($old_service_name, $new_service_name)
    {
        $old_path = config('devless')['views_directory'].$old_service_name;
        $new_path = config('devless')['views_directory'].$new_service_name;

        return (rename($old_path, $new_path)) ? true : false;
    }
}
