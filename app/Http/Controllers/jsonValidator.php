<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class jsonValidator extends Controller
{

    public $ERROR_HEAP = [
    'Maximum stack depth exceeded',
    'Underflow or the modes mismatch',
    'Unexpected control character found',
    'Syntax error, malformed JSON',
    'Malformed UTF-8 characters, possibly incorrectly encoded',
    'Unknown error',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $JSON[] = <<<EOT

{  
   "resource":[  
      {  
         "name":"orders",
         "description":null,
         "primary_key":null,
         "field":[  
            {  
               "name":"customer",
               "type":"reference",
               "db_type":"integer",
               "length":null,
               "precision":null,
               "scale":null,
               "default":null,
               "required":true,
               "allow_null":false,
               "fixed_length":false,
               "supports_multibyte":false,
               "is_primary_key":false,
               "is_unique":false,
               "is_index":false,
               "is_foreign_key":true,
               "ref_table":"customers",
               "ref_fields":"id",
               "validation":null
            }
         ],
         "related":[  
            {  
               "name":"products_by_product",
               "always_fetch":false,
               "type":"belongs_to",
               "field":"product",
               "ref_table":"products",
               "ref_fields":"id"
            }
         ],
         "connector":[  
            {  
               "driver":"mysql",
               "host":"127.0.0.1",
               "database":"devless-rec",
               "username":"root",
               "password":"password",
               "charset":"utf8",
               "collation":"utf8_unicode_ci",
               "prefix":""
            }
         ],
         "access":31
      }
   ]
}

EOT;
//var_dump(json_decode($JSON));
#var_dump($JSON[0]);
$ERROR_STACK = 0; 
foreach ($JSON as $string) {
    json_decode($string);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            #echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            #echo ' - Maximum stack depth exceeded';
            $ERROR_STACK = 1;
        break;
        case JSON_ERROR_STATE_MISMATCH:
            #echo ' - Underflow or the modes mismatch';
            $ERROR_STACK = 2;
        break;
        case JSON_ERROR_CTRL_CHAR:
            #echo ' - Unexpected control character found';
            $ERROR_STACK = 3;
        break;
        case JSON_ERROR_SYNTAX:
            #echo ' - Syntax error, malformed JSON';
            $ERROR_STACK = 4;
        break;
        case JSON_ERROR_UTF8:
            #echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            $ERROR_STACK = 5;
        break;
        default:
            $ERROR_STACK = 6;
        break;
    }

    #echo PHP_EOL;
 }

   if($ERROR_STACK == 0 ){
          return json_decode($JSON[0]);
    }else{
        return $this->ERROR_HEAP[$ERROR_STACK];
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
