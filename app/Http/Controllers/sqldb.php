<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class sqldb extends Controller
{
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
     *create tables with fields from json schema
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #get schema from jsonValidator
        $json = app('App\Http\Controllers\Validator')->index();
        $json = json_decode(json_encode($json), true);
        $
        $json = $json['resource'][0];  
        #check json status and structure before processing 
       print  strlen(sizeof($json['field']));
        if($json['connector'][0]['database'] == 'sqlite'){

            $json['connector'][0]['database'] =  __DIR__.
            '/../../../database//devless-rec.sqlite3';

        }
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

         die(); 

         #foreach()   
         $columns = [];
         $newtableschema = array(
            'tablename' => $json['name'],
           'colnames' => array('One', 'Two', 'Three', 'Four')
           );
    


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
          \Config::set('database.connections.DB_CONFIG_NAME', $conn);

         $DB = \DB::connection('DB_CONFIG_NAME');
         $users = $DB->table('role')->join('app','role.id','=','app.role_id')->select('role.*','app.*')->get() ;

         $users = (array)$users;
         echo  json_encode($users).",";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //email
        
       # $result = app('App\Http\Controllers\Validator')->validschema($json['field']['db_type']);
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


//      try{
    //          \Schema::connection('DB_CONFIG_NAME')->create($newtableschema['tablename'], function($table) use($newtableschema) {

    // // So now you can access the $newtableschema variable here
    // // Rest of your code...
    //             $table->string('name');
    //         });
    //      }
    //      catch(\Exception $e){
    //         echo $e->getMessage().'<br>';
    //         print "*****************************";
    //         print_r($e->getCode()).'<br>';
    //         print "*****************************";
    //         print_r($e->getTrace()).'<br>';
    //         print "*****************************";  

    //     }
    