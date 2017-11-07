<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use App\Helpers\DevlessHelper as DH;

class OpenController extends Controller
{

    public function gateway(Request $request, $file, $method, $params)
    {
        $params = array_values(json_decode($params, true));
        include base_path()."/app/OpenApis/$file.php";
        $class = new $file();
        return $class->$method(...$params);
    }

    public function downloadFile(Request $request, $file, $method, $page, $params)
    {
        $params = array_values(json_decode($params, true));
        include base_path()."/app/OpenRender/$file.php";
        $class = new $file();
        $results = $class->$method(...$params);
        $html = view($page)->with($results)->render();
        file_put_contents($params[0].'_'.$params[1].'.html', $html);
         return \Response::download(
             $params[0].'_'.$params[1].'.html',
             $params[0].'_'.$params[1].'.html'
         )->deleteFileAfterSend(true);
    }
    

    public function render(Request $request, $file, $method, $page, $params)
    {
        $params = array_values(json_decode($params, true));
        include base_path()."/app/OpenRender/$file.php";
        $class = new $file();
        $results = $class->$method(...$params);
        return view($page)->with($results);
    }
}
