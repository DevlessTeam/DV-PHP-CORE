<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
#use App\Helpers\SchemaHelper;
use App\Exceptions\Handler as error;
class schemaController extends Controller
{
    public  $db_types = [
   'text'      => 'string',
  'textarea'   => 'longText',
  'integer'    => 'integer',
  'money'      => 'double',
  'password'   => 'string',
  'percentage' => 'integer',
  'url'        => 'string',
  'timestamp'  => 'timestamp',
  'boolean'    => 'boolean',
  'email'      => 'string',
  'reference'  => 'integer',    
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
              
     #dynamically create columns with schema builder 
    $db_type = $this->db_types;
    $table_meta_data = []; 
    \Config::set('database.connections.DYNAMIC_DB_CONFIG', $conn);
    \Schema::connection('DYNAMIC_DB_CONFIG')->
    create($json['name'],function(\Illuminate\Database\Schema\Blueprint $table) 
            use($json,$db_type)
    {       
        #$col_name = $json['field'][0]['name'];
        #default field
        $table->increments('id');
        #per each field 
       foreach($json['field'] as $field ){
                #checks if fieldType and references exist    
                $this->field_check( $field, $field['ref_table']); 
                
                #generate columns 
                $this->column_generator($field, $table, $db_type);
                
           }
    });

    }
    /**
     *check if fields exist
     *
     * @param column fields (array)  $field
     * @param  table_name   $table_name
     * @return true
     */
    public function field_check( $field, $col_name)
    {      
            #check if soft data type has equivalent db type
            if(isset($this->db_types[$field['field_type']]))
            {   
                //
                
            }
            else
            {
                 Helper::interrupt(600, $field['field_type'].' does not exist');
            }
           
            if($field['field_type'] == "reference")
            {    
                if(! \Schema::connection('DYNAMIC_DB_CONFIG')->
                        hasTable($col_name, $field['ref_table'])) 
                {
                 
                         //
                    Helper::interrupt(601, 'referenced table '
                            .$field['ref_table'].' does not exist');
                }
                
            }
    }
    
    /**
     * check column constraints
     *
     * @param  column fields (array)  $field
     * @return int 
     */
    public function check_premise($field)
    {   
        #create column with default and reference 
        if($field['field_type'] =='reference' && $field['default'] !== null)
        {
            return 4;
            
        }
        else if($field['field_type'] =='reference' && $field['default'] == null)
        {
            
            return 3;
        }
        else if($field['field_type'] !='reference' && $field['default'] != null)
        {
            return 2;
        }
        if(($field['field_type'] !=='reference' && $field['default'] == null))
        { 
            
            return 1;
        }
        else
        {
            Helper::interrupt(602, 'Database schema could not be created');
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  array $field 
     * @param  object $table
     * @return object
     */
    public function column_generator($field, $table, $db_type)
    {
        $column_type = $this->check_premise($field);
        
        if($column_type == 4)
        {
            
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
                    ->unsigned();
            $table->foreign($field['ref_table'].'_id')->references('id')
                    ->on($field['ref_table']);
        }
        else if($column_type == 3)
        {
            
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
                    ->unsigned();
            $table->foreign($field['ref_table'].'_id')->references('id')
                    ->on($field['ref_table'])->default($field['default']);
        }
        else if($column_type == 2)
        { 
            $table->$db_type[$field['field_type']]
                ($field['name'])->default($field['default']); 
        }
        else if($column_type == 1)
        {
            $table->$db_type[$field['field_type']]
                ($field['name']); 
        }
        else
        {
            Helper::interrupt(602, 'Database schema could not be created');
        }
    }
    
}

#check if table exist before attempting to create   