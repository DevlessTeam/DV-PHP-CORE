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
class DbController extends Controller
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
     * create new table schema .
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
    {       $service_id = $payload['id'];
            #setup db connection 
            $connector = $this->_connector($payload);
            $db = \DB::connection('DYNAMIC_DB_CONFIG');
            foreach($payload['params'] as $table){
                 //check field if valid proceed with adding data 
                $table_data = $this->
                    _validate_fields($table['name'],
                           $service_id, $table['field'], true);
                
                 $output = $db->table($table['name'])->insert($table_data);
            }
            
            if($output)
            {
                Helper::interrupt(609,'Data has been added to '.$table['name']
                        .' table succefully');
            }
           
        }

        /**
         * Update the specified resource in storage.
         * @param  string  $resource 
         * @param array $payload payload 
         * @return 
         */
        public function update($resource, $payload)
        {
            
            $connector = $this->_connector($payload);
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
                
                Helper::interrupt(619, 
                        'table '.$payload['params'][0]['name']." updated successfuly");

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
            
            $connector = $this->_connector($payload);
            $db =   \DB::connection('DYNAMIC_DB_CONFIG');
            //check if table name is set 
         $table_name = $payload['params'][0]['name'];
         $destroy_query = '$db->table("'.$table_name.'")';
         if(isset($payload['params'][0]['params'][0]['drop']))
         {
             if($payload['params'][0]['params'][0]['drop'])
             {
                \Schema::connection('DYNAMIC_DB_CONFIG')->dropIfExists($table_name);
                \DB::table('table_metas')->where('table_name',$table_name)->delete();
                Helper::interrupt(613,'dropped table succefully');
                $task = 'drop';
             }
         }
         if(isset($payload['params'][0]['params'][0]['where'] ))
         {
             if($payload['params'][0]['params'][0]['where'] == true  )
            {

             $where = $payload['params'][0]['params'][0]['where'];
             $where = str_replace(",", "','", $where);
             $where = "'".$where."'";
             $destroy_query = $destroy_query.'->where('.$where.')';
             $task ='where';


            }
         }
         $element = 'row';
         if(isset($payload['params'][0]['params'][0]['truncate'] ) )
        {

             if($payload['params'][0]['params'][0]['truncate'] == true)
             {
                $destroy_query = $destroy_query.'->truncate()';
                $tasked ='truncated';$task = 'truncate';
                
                
             }
        }
        else if(isset($payload['params'][0]['params'][0]['delete'] ))
        {

            if($payload['params'][0]['params'][0]['delete'] == true)
            {

                  
                $destroy_query = $destroy_query.'->delete()';
                $tasked ='deleted'; $task = 'delete'; 
                
             
            }   

        } 
        else
        {
            Helper::interrupt(615);
        }
        
        $destroy_query = $destroy_query.';'; 
        $result = eval('return'.$destroy_query);
        if($result == false && $result != null){Helper::interrupt(614, 'could not '.$task.' '.$element);}
        Helper::interrupt(626, 'The table or field has been '.$task);
            
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
        $connector = $this->_connector($payload);
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
               $related = $this->_find_relations($table_name, $wanted_relationships, $db);
               unset($payload['params']['relation']);}
               
               if(isset($payload['params']['order']))
            {
               $order_by = $payload['params']['order'];
               $table_name = $payload['params']['table'];
              $complete_query = $complete_query
                    . '->orderBy("'.$payload['params']['order'][0].'" )' ;
                  unset($payload['params']['order']);}
            unset($payload['params']['table'],$payload['params']['size']
                    ); 
            foreach($payload['params'] as $key => $query)
            {
                foreach($query as $one)
                {  
                    #prepare query for order and where
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
            $response = Response::respond(625,null,$output);
            echo ($response);
            
        }
      else{
          Helper::interrupt(611);
      }
          }
    #remember to allow  expand db elements 
    public function create_schema($resource, array $json)
    {
        
    #set path in case connector is sqlite
    $id = $json['id'];
    
    #connectors mysql pgsql sqlsrv sqlite
    $connector = $this->_connector($json);
    
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
                        $field['field_type'] = strtolower($field['field_type']);
                        #checks if fieldType and references exist
                        $this->field_type_exist($field); 
                        #generate columns 
                        $this->column_generator($field, $table, $db_type);

                    }
            //store table_meta details 
            });
            $this->_set_table_meta($json);
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
    public function field_type_exist( $field)
    {      
        
            #check if soft data type has equivalent db type
        if(!isset($this->db_types[$field['field_type']]))
        {   
            
            Helper::interrupt(600, $field['field_type'].' does not exist');
            
        }
        if(strtolower($field['field_type']) == "reference")
        {    
            if(! \Schema::connection('DYNAMIC_DB_CONFIG')->
                hasTable($field['ref_table'])) 
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
        $unique = "";
        if($field['is_unique'] == 'true'){$unique = 'unique';}
        if($column_type == 4)
        {
            
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
            ->unsigned()->$unique();
            $table->foreign($field['ref_table'].'_id')->references('id')
            ->on($field['ref_table'])->onDelete('cascade');
        }
        else if($column_type == 3)
        {
            
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
            ->unsigned()->$unique();
            $table->foreign($field['ref_table'].'_id')->references('id')
            ->on($field['ref_table'])->default($field['default'])
            ->onDelete('cascade');
        }
        else if($column_type == 2)
        { 
            $table->$db_type[$field['field_type']]
            ($field['name'])->default($field['default'])->onDelete('cascade')
                    ->$unique(); 
        }
        else if($column_type == 1)
        {
            $table->$db_type[$field['field_type']]
            ($field['name'])->onDelete('cascade')->$unique(); 
        }
        else
        {
            Helper::interrupt(602, 
                    'For some reason database schema could not be created');
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
        if($driver == 'sqlite')
        {
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
    /*
     * access different database connections
     * 
     * @param $payload request parameters
     * @return boolean
     */
    private function _connector($connector_params)
    {
        
        $driver = $connector_params['driver'];
        
        //get current database else connect to remote
        if($driver == 'default')
        {
            $default_database = config('database.default');
            $default_connector = config('database.connections.'.$default_database);
            
            $driver   = $default_connector['driver'];
            $hostname = (isset($default_connector['hostname']))? $default_connector['hostname']:
                        $default_connector['host'];
            $database = $default_connector['database'];
            $username = $default_connector['username'];
            $password = $default_connector['password'];
        }
        else
        {
            $driver   = $connector_params['driver'];
            $hostname = $connector_params['hostname'];
            $database = $connector_params['database'];
            $username = $connector_params['username'];
            $password = $connector_params['password'];
        }
        $this->db_socket($driver, $hostname, $database, $username, $password);
          
        return true;
    }
    
   private function _find_relations($table_name, $wanted_relationships, $db)
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
   private function _set_table_meta($schema)
   {
       
       \DB::table('table_metas')->insert(['schema'=>  json_encode($schema),
               'table_name'=> $schema['name'],'service_id'=>$schema['id']]);
       
       
       return true;
   }
   /*
    * validate entry data against schema field type
    *
    * @param string  $service_id
    *
    * @return array
    */
   private function _get_tableMeta($service_id)
   {
       $tableMeta =\DB::table('table_metas')->
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
                            
                        }
                        return $tableMeta;
   }
   
   /*
    * Check if a connection can be made to database
    * @param array $connection_params (hostname,username,password,driver,)
    * 
    */
   public function check_db_connection(array $connection_params){
       
       $connector = $this->_connector($connection_params);
       //dd(\DB::connection('DYNAMIC_DB_CONFIG'));
       return true; 
   }
   
   /*
    * validate entry data against schema field type
    *
    * @param string $table_name 
    * @param string $service_id
    * @param array  $field_names
    * @return boolean
    */
   private function _validate_fields($table_name,$service_id, $table_data, 
           $check_password=false)
   {
       
       $table_meta = $this->_get_tableMeta($service_id);
       $hit = 0; $check = 0; $count = 0;
       foreach($table_meta as $schema)
       {
          
          
           if($schema['schema']['name'] == $table_name)
           { 
             
                foreach($table_data as $field_unit)
                {
                    foreach($field_unit as $field => $field_value)
                    {
                            foreach($schema['schema']['field'] as $fields)
                            {  
                                if($fields['name'] == $field)
                                {
                                    //pass field_type and value to validate 
                                    $err_msg = 
                                       Helper::field_check($field_value,
                                               $fields['field_type']);
                                    
                                    if($check_password == "true" &&
                                            $fields['field_type']== "password" )
                                        {$table_data[$count]['password']=
                          Helper::password_hash($table_data[$count]['password']);
                                         
                                        }
                                        
                                    if(is_object($err_msg) == true)
                                    {
                                        
                                         Helper::interrupt(616, $err_msg);
                                        
                                        
                                    }
                                        
                                }
                            }
                    }
                    $count++;
                }
                $hit = 1;
           }
           
          
       }
       
       if($hit == 0)
       {
            Helper::interrupt(617);
       }

        if($check_password == "true")
        {

           return $table_data;
        }
        else
        {
           return true; 
        }
   }
        
}


//TODO:test drop and drop meta
//TODO:prefix tables      