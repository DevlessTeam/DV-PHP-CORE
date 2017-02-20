<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 20/02/2017
 * Time: 4:15 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class herokuController extends Controller
{
    public function extract_all_view_files()
    {
        return true;
    }

    public function extract_view_file($service_name)
    {
        return true;
    }

    public function archive_view_files($service_name)
    {
        return true;
    }
}