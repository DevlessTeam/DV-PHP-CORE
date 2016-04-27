<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class schemaController extends Controller
{
    public  $db_type = [
    'Text' => 'string',
    'Textarea' => 'longText',
    'Integer' => 'integer',
    'Money' => 'double',
    'Password' => 'string',
    'Percentage' => 'integer',
    'Url' => 'string',
    'Timestamp' => 'timestamp',
    'Boolean' => 'boolean',
    'email' => 'string',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->create_schema($request['resource']);
        
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
    #remember to allow  expand db elements 
    public function create_schema(array $json)
    {
      $json = $json[0];  
      #set path incase connector is sqlite
      if($json['connector'][0]['driver'] == 'sqlite'){
        $json['connector'][0]['database'] =  __DIR__.
        '/../../../database/devless-rec.sqlite3';

    }
    dd($json['field'][0]['name']);
        #connectors mysql pgsql sqlsrv sqlite
    $conn = array(
        'driver'    => $json['connector'][0]['driver'],
        'host'      => $json['connector'][0]['host'],
        'database'  => $json['connector'][0]['database'],
        'username'  => $json['connector'][0]['username'],
        'password'  => $json['connector'][0]['password'],
        'charset'   => $json['connector'][0]['charset'],
        'collation' => $json['connector'][0]['collation'],
        'prefix'    => $json['connector'][0]['prefix'],
        );

         #set array up for schema conversion 
    $db_type = $this->db_type;
    $table_meta_data = []; 
    \Config::set('database.connections.DYNAMIC_DB_CONFIG', $conn);
    \Schema::connection('DYNAMIC_DB_CONFIG')->
    create($json['field'][0]['name'],function($table) use($json,$db_type)
    {       
        $table->increments('id');
        #dynamically create columns 
       foreach($json['field'] as $field ){
        $table->$db_type[$field['field_type']]
        ($field['field_type']);
        
    }

    
});
    
}

public function update_schema()
{
        //
}

}
