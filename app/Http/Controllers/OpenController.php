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

    public function render(Request $request, $file, $method, $page, $params) {
    	$params = array_values(json_decode($params, true));
    	include base_path()."/app/OpenRender/$file.php";
    	$class = new $file();
    	$results = $class->$method(...$params);
    	return view($page)->with($results);
    }

    
}