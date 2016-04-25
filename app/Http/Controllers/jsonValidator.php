<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class jsonValidator extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $JSON[] = <<<EOT
[  
   {  
      "id":3,
      "name":"file_manager",
      "description":"An application for managing file services.",
      "is_active":1,
      "created_date":"2016-04-14 10:03:51",
      "last_modified_date":"2016-04-14 10:03:51",
      "created_by_id":null,
      "last_modified_by_id":1,
      "api_key":"b5cb82af7b5d4130f36149f90aa2746782e59a872ac70454ac188743cb55b0ba",
      "type":3,
      "path":"filemanager\/index.html",
      "url":null,
      "storage_service_id":null,
      "storage_container":null,
      "requires_fullscreen":0,
      "allow_fullscreen_toggle":1,
      "toggle_location":"top",
      "role_id":1
   },
   {  
      "id":4,
      "name":"add_angular2",
      "description":"An address book app for Angular 2 showing user registration, user login, and CRUD.",
      "is_active":1,
      "created_date":"2016-04-14 10:04:16",
      "last_modified_date":"2016-04-14 10:04:16",
      "created_by_id":1,
      "last_modified_by_id":1,
      "api_key":"c9ef892c38f3828609a3714dc369e3b8127796f6953b1ed4da01b093c4f7f9e5",
      "type":1,
      "path":"add_angular2\/index.html",
      "url":"index.html",
      "storage_service_id":3,
      "storage_container":"AddAngular2",
      "requires_fullscreen":0,
      "allow_fullscreen_toggle":1,
      "toggle_location":"top",
      "role_id":1
   },
   {  
      "id":5,
      "name":"Devless_Ecommerce_Module",
      "description":"Manage your ecommerce platform with ease",
      "is_active":1,
      "created_date":"2016-04-14 14:05:35",
      "last_modified_date":"2016-04-14 14:05:35",
      "created_by_id":1,
      "last_modified_by_id":1,
      "api_key":"3fc57b743aa359814fcfa466b8057897e8ecf201d1c7782ecb94692fb1db89ee",
      "type":1,
      "path":"ecommerce",
      "url":null,
      "storage_service_id":3,
      "storage_container":"ecommerce",
      "requires_fullscreen":0,
      "allow_fullscreen_toggle":1,
      "toggle_location":"top",
      "role_id":1
   }
]
EOT;
//var_dump(json_decode($JSON));
var_dump($JSON[0]);
foreach ($JSON as $string) {
    json_decode($string);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }

    echo PHP_EOL;
}

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        # silence is golden 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
