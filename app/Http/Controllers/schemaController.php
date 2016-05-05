<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
#use App\Helpers\SchemaHelper;
use App\Exceptions\Handler as error;
use Illuminate\Filesystem\Filesystem as files;
use App\Helpers\Response as Response;  
use \Illuminate\Database\Schema\Blueprint as Blueprint;
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
    
    public $query_params = [
    'order'    => 'orderBy',
    'where'    => 'where',
    'take'     => 'take',
    'relation' => 'relation'
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * api/v1/schema
     */
    public function store(Request $request)
    {
        //
        $resource = 'schema';
        
        $this->create_schema($resource, $request['resource']);
        
    }

    /**
     * query for data from db 
     *
     * @param  string  $resource
     * @return \Illuminate\Http\Response
     * 
     */
    public function add_data($resource, $payload)
    {       
            #$payload['table'] = (array)$payload['tableMeta'][0]['schema'];
            #dd((array)$payload['table']['schema']);
            #setup db connection 
            $connector = $this->connector($payload);
            $db = \DB::connection('DYNAMIC_DB_CONFIG');
            foreach($payload['params'] as $table){
                #['field_type']
            dd($payload['tableMeta'][0],$table['field']);
            $result = Helper::field_check($this->db_types[$table['field']], 
                    $table['field']);   
            $output = $db->table($table['name'])->insert($table['field']);
            }
            if($output)
            {
                Helper::interrupt(609,'Data has been added to '.$table['name']
                        .' table succefully');
            }
            //remember to add field validation 
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
         * @param  string  $resource 
         * @param array $payload payload 
         * @return 
         */
        public function update($resource, $payload)
        {
            //
            $connector = explode(',',$payload['db_definition']);
            $connector = $this->connector($payload);
            $db =   \DB::connection('DYNAMIC_DB_CONFIG');

            if(isset($payload['params'][0]['name'],
                    $payload['params'][0]['params'][0]['where'],
                        $payload['params'][0]['params'][0]['data']))
            {
                $table_name = $payload['params'][0]['name'];
                $where  = $payload['params'][0]['params'][0]['where'];
                $explotion = explode(',', $where);
                $data =  $payload['params'][0]['params'][0]['data'];
                $db->table($table_name)
                ->where($explotion[0],$explotion[1])
                ->update($data[0]);

            }
            else
            {
                Helper::interrupt(614); 
            }
            

        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  string  $resource
         * @param array $payload payload from request 
         * @return \Illuminate\Http\Response
         */
        public function destroy($resource, $payload)
        {
            //
            $connector = explode(',',$payload['db_definition']);
            $connector = $this->connector($payload);
            $db =   \DB::connection('DYNAMIC_DB_CONFIG');
            //check if table name is set 

         $table_name = $payload['params'][0]['name'];
         $destroy_query = '$db->table("'.$table_name.'")';
         if(isset($payload['params'][0]['params'][0]['drop']))
         {
             if($payload['params'][0]['params'][0]['drop'])
             {
             \Schema::connection('DYNAMIC_DB_CONFIG')->dropIfExists($table_name);
             Helper::interrupt(613);
             $task = 'drop';
             }
         }
         if(isset($payload['params'][0]['params'][0]['where'] ))
         {
             if($payload['params'][0]['params'][0]['where'] == true  )
            {

             $where = $payload['params'][0]['params'][0]['where'];
             $destroy_query = $destroy_query.'->where('.$where.')';
             $task ='where';


            }
         }
         if(isset($payload['params'][0]['params'][0]['truncate'] ) )
        {

             if($payload['params'][0]['params'][0]['truncate'] == true)
             {
                $destroy_query = $destroy_query.'->truncate()';
                $task ='truncate';
             }
        }
        else if(isset($payload['params'][0]['params'][0]['delete'] ))
        {

            if($payload['params'][0]['params'][0]['delete'] == true)
            {

                  
                $destroy_query = $destroy_query.'->delete()';
                $task ='deleted';  

            }   

        } 
        else if(isset($payload['params'][0]['params'][0]['drop'] )){

            $destroy_query = $destroy_query.'->drop()';
            $task ='dropped';  
             
        }
        else
        {
            Helper::interrupt(615);
        }
        $destroy_query = $destroy_query.';';   
        $result = eval('return'.$destroy_query);
        Helper::interrupt(614, 'The table has been '.$task);
    }
        
    
    /**
    * query a particular table for data .
    *
    * @param  string  $resource
    * @param array $payload payload from request 
    * @return \Illuminate\Http\Response
    */
    public function db_query($resource, $payload)
    {
        $connector = explode(',',$payload['db_definition']);
        $connector = $this->connector($payload);
        $db =   \DB::connection('DYNAMIC_DB_CONFIG');
        //check if table name is set 
        if(isset($payload['params']['table']))
       {    
            $base_query = '$db->table("'.$payload['params']['table'][0].'")';
            //check if pagination is set 
            (isset($payload['params']['size']))?
            $complete_query = $base_query
                    . '->take('.$payload['params']['size'][0].')' :
            $complete_query = $base_query.'->take(100)' ;    
            $related =[];
            if(isset($payload['params']['relation']))
            {
               $wanted_relationships = $payload['params']['relation'];
               $table_name = $payload['params']['table'];
               $related = $this->find_relations($table_name, $wanted_relationships, $db);
               unset($payload['params']['relation']);}
               
            unset($payload['params']['table'],$payload['params']['size'][0]
                    );    
            foreach($payload['params'] as $key => $query)
            {
                foreach($query as $one)
                {
                    #prepare query for order and which 
                    if(isset($this->query_params[$key]))
                    {   
                        
                        $query_params = explode(',', $one);
                        if(isset($query_params[1],$query_params[0])){
                        $complete_query = $complete_query.
                                '->'.$this->query_params[$key].'("'.$query_params[0].
                                '","'.$query_params[1].'")';
                        }
                        else
                        {
                            Helper::interrupt(612);
                        }
                    }
                    else
                    {
                        Helper::interrupt(610);
                    }
                
                }

                
            }
            $complete_query = 'return '.$complete_query.'->get();';
            $output = eval($complete_query);
            $output['related'] = $related;
            $response = Response::respond(612,"Got response sucessfully",$output);
            echo ($response);
            
        }
      else{
          Helper::interrupt(611);
      }
          }
    #remember to allow  expand db elements 
    public function create_schema($resource, array $json)
    {
        
      #set path incase connector is sqlite

    $id = $json['id'];
        #connectors mysql pgsql sqlsrv sqlite
    $connector = explode(',',$json['db_definition']);
    $connector = $this->connector($json);
     #dynamically create columns with schema builder 
    $db_type = $this->db_types;
    $table_meta_data = []; 
    $json = $json['params'][0];
    $json['id'] = $id;
     if(! \Schema::connection('DYNAMIC_DB_CONFIG')->
                hasTable($json['name'])) 
        {
            

            \Schema::connection('DYNAMIC_DB_CONFIG')->
            create($json['name'],function(Blueprint 
                    $table) 
                use($json,$db_type)
                {       
                #default field
                    $table->increments('id');
                #per  field 
                    foreach($json['field'] as $field ){
                        #checks if fieldType and references exist    
                        $this->field_check( $field, $field['ref_table']); 
                        #generate columns 
                        $this->column_generator($field, $table, $db_type);

                    }
            //store table_meta details 
            });
            $this->table_meta($json);
            Helper::interrupt(606);
        }
    else
    {
    Helper::interrupt(603, $json['name']." table already exist");
    }

}
    /**
     *check if field exist
     *
     * @param column fields (array)  $field
     * @param  table_name   $table_name
     * @return true
     */
    public function field_check( $field, $col_name)
    {      
            #check if soft data type has equivalent db type
        if(!isset($this->db_types[$field['field_type']]))
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
            ->on($field['ref_table'])->onDelete('cascade');
        }
        else if($column_type == 3)
        {
            
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
            ->unsigned();
            $table->foreign($field['ref_table'].'_id')->references('id')
            ->on($field['ref_table'])->default($field['default'])
            ->onDelete('cascade');
        }
        else if($column_type == 2)
        { 
            $table->$db_type[$field['field_type']]
            ($field['name'])->default($field['default'])->onDelete('cascade'); 
        }
        else if($column_type == 1)
        {
            $table->$db_type[$field['field_type']]
            ($field['name'])->onDelete('cascade'); 
        }
        else
        {
            Helper::interrupt(602, 'Database schema could not be created');
        }
    }
   /*
    * access the schema class from this method
    * @param string resource name $resource 
    * @param array payload $payload 
    */    
    public function access_db($resource, $payload)
    {
        
                
            if($payload['method'] == 'GET')
            {
                $this->db_query($resource, $payload);
            }
            else if($payload['method'] == 'POST')
            {

                $this->add_data($resource, $payload);
            }
            else if($payload['method'] == 'PATCH')
            {
                $this->update($resource, $payload);
            }
            else if($payload['method'] == 'DELETE')
            {
                $this->destroy($resource, $payload);
            }
            else
            {
                Helper::interrupt(607);
            }
            
    }
    public function db_socket($driver, $host, $database,$username, $password,
            $charset='utf8', $prefix='', $collation='utf8_unicode_ci')
    {
            if($driver == 'sqlite'){
            $database =  __DIR__.
            '/../../../database/devless-rec.sqlite3';

        }
         $conn = array(
        'driver'    => $driver,
        'host'      => $host,
        'database'  => $database,
        'username'  => $username,
        'password'  => $password,
        'charset'   => $charset,
        'prefix'    => $prefix,
        );
         if($driver == 'mysql')
         {
             $conn['collation'] = $collation;
         }
         \Config::set('database.connections.DYNAMIC_DB_CONFIG', $conn);
    }
    
    private function connector($payload)
    {
        $connector = explode(',',$payload['db_definition']);
        $index = 0;
        foreach($connector as $conn)
        {
            $connector[$index] = substr($conn, ($pos = strpos($conn, '=')) 
                 !== false ? $pos + 1 : 0);
            $index++;
        }
         $this->db_socket($connector[0], $connector[1], 
            $connector[2],$connector[3],$connector[4]);
          
        
    }
    
   private function find_relations($table_name, $wanted_relationships, $db)
   {
       
       //simulations of getting relations from table meta   
       $table_models = [
        'orders' => [
            'products'
        ],
        'products' => [
            'orders'
        ]  
       ];
       $related = [];
       foreach ($wanted_relationships as $relationship)
       {
           if($relationship == '*')
           {
               //check the model and grab all relations 
               foreach($table_models[$table_name[0]] as $table)
               {
                         $related[$table] = $db->table($table)->get();
                         
               }
              
           }
           else
           {
                $related[$relationship] = $db->table($relationship)->get();
           }
       }
            return $related;
        
   }
   private function set_table_meta($schema)
   {
       
       \DB::table('tableMeta')->insert(['schema'=>  json_encode($schema),
               'table_name'=> $schema['name'],'service_id'=>$schema['id']]);
       
       
       return true;
   }
   private function get_tableMeta($service_id)
   {
       $tableMeta =\DB::table('tableMeta')->
                                where('service_id',$service_id)->get();
                        $tableMeta = json_decode(json_encode($tableMeta),true);
                        $count = 0;
                        foreach($tableMeta as $table)
                        {
                            $tableMeta[$count]['schema'] = 
                               (array)json_decode($tableMeta[$count]['schema']);

                             $tableMeta[$count]['schema'] = 
                             json_decode(json_encode($tableMeta
                                [$count]['schema']),true);
                            $count ++;
                            return $tableMeta;
                        }
   }
   
   private function get_field()
   {
       $this->get_tableMeta($service);
   }
}
