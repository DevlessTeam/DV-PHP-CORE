<?php
namespace Devless\Schema;

use App\Helpers\Helper;
use App\Helpers\Response as Response;
use App\Http\Controllers\ServiceController as Service;
use Illuminate\Database\Schema\Blueprint as Blueprint;
use Illuminate\Http\Request;

class DbHandler
{
    public $db_types = [
        'text'       => 'string',
        'textarea'   => 'longText',
        'integer'    => 'integer',
        'decimals'   => 'double',
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
        'relation' => 'relation',
    ];
    public $dbActionAssoc = [
    'GET'    => 'query',
    'POST'   => 'create',
    'PATCH'  => 'update',
    'DELETE' => 'delete',
    ];
    private $dbActionMethod = [
    'GET'    => 'db_query',
    'POST'   => 'add_data',
    'PATCH'  => 'update',
    'DELETE' => 'destroy',
    ];
    /**
     * Access db functions based on request method type.
     *
     * @param string resource name $resource
     * @param array payload        $payload
     *
     * @return \App\Helpers\json|\Illuminate\Http\Response
     */
    public function access_db($payload)
    {
        $payload['user_id'] = '';
        $request = $payload['method'];
        $db_action = (isset($this->dbActionAssoc[$request])) ? $this->dbActionAssoc[$request]
                     : Helper::interrupt(607);
        $payload = $this->set_auth_id_if_required($db_action, $payload);
        $dbActionName = $this->dbActionMethod[$request];
        return $this->$dbActionName($payload);
    }
    /**
     * Create new table schema.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *                                   api/v1/schema
     */
    public function store(Request $request)
    {
        //
        $resource = 'schema';
        $this->create_schema($request['resource']);
    }
    /**
     * query for data from db.
     *
     * @param $payload
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param string $resource
     */
    public function add_data($payload)
    {
        $service_id = $payload['id'];
        $service_name = $payload['service_name'];
        //setup db connection
        $connector = $this->_connector($payload);
        $db = \DB::connection('DYNAMIC_DB_CONFIG');
       
        (isset($payload['params'][0]['name']) && count($payload['params'][0]['name'])> 0
                && gettype($payload['params'][0]['field']) == 'array' || isset($payload['params'][0]['field'][0]) )? true :
        Helper::interrupt(641);
        foreach ($payload['params'] as $table) {
            $table_name = $table['name'];
            if (!\Schema::connection('DYNAMIC_DB_CONFIG')->
            hasTable($service_name.'_'.$table_name)) {
                Helper::interrupt(634);
            }
            //check data against field type before adding data
            $table_data = $this->_validate_fields(
                $table_name,
                $service_name,
                $table['field'],
                true
            );
            //assigning autheticated user id
            $table_data[0]['devless_user_id'] = $payload['user_id'];
            $output = $db->table($service_name.'_'.$table['name'])->insert($table_data);
        }
        if ($output) {
            return Response::respond(609, 'Data has been added to '.$table['name']
            .' table succefully');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param array $payload payload
     *
     * @return \App\Helpers\json
     *
     * @internal param string $resource
     */
    public function update($payload)
    {
        $connector = $this->_connector($payload);
        $db = \DB::connection('DYNAMIC_DB_CONFIG');
        $service_name = $payload['service_name'];
        if (isset(
            $payload['params'][0]['name'],
            $payload['params'][0]['params'][0]['where'],
            $payload['params'][0]['params'][0]['data']
        )) {
            $table_name = $service_name.'_'.$payload['params'][0]['name'];
            if (!\Schema::connection('DYNAMIC_DB_CONFIG')->
            hasTable($table_name)) {
                return Helper::interrupt(634);
            }
            $where = $payload['params'][0]['params'][0]['where'];
            $explosion = explode(',', $where);
            $data = $payload['params'][0]['params'][0]['data'];
            if ($payload['user_id'] !== '') {
                $result = $db->table($table_name)
                ->where($explosion[0], $explosion[1])
                ->where('devless_user_id', $payload['user_id'])
                ->update($data[0]);
            } else {
                $result = $db->table($table_name)
                ->where($explosion[0], $explosion[1])
                ->update($data[0]);
            }
            if ($result == 1) {
                return Response::respond(
                    619,
                    'table '.$payload['params'][0]['name'].' updated successfuly'
                );
            } else {
                Helper::interrupt(629, 'Table '.$payload['params'][0]['name'].' could not be updated');
            }
        } else {
            Helper::interrupt(614);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param array $payload payload from request
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param string $resource
     */
    public function destroy($payload)
    {
        $connector = $this->_connector($payload);
        $db = \DB::connection('DYNAMIC_DB_CONFIG');
        //check if table name is set
        $service_name = $payload['service_name'];
        $table = $payload['params'][0]['name'];
        //remove service appendage from service
        if (($pos = strpos($table, $service_name.'_')) !== false) {
            $tableWithoutService = substr($table, $pos + 1);
        } else {
            $tableWithoutService = $table;
        }
        $table_name = ($tableWithoutService == $payload['params'][0]['name'])
                ? $service_name.'_'.$tableWithoutService:
                  $payload['params'][0]['name'];
        if (!\Schema::connection('DYNAMIC_DB_CONFIG')->
        hasTable($table_name)) {
            Helper::interrupt(634);
        }
        //
        if ($payload['user_id'] !== '') {
            $user_id = $payload['user_id'];
            $destroy_query = '$db->table("'.$table_name.'")->where("devless_user_id",'.$user_id.')';
        } else {
            $destroy_query = '$db->table("'.$table_name.'")';
        }
        if (isset($payload['params'][0]['params'][0]['drop'])) {
            if ($payload['params'][0]['params'][0]['drop'] == true) {
                \Schema::connection('DYNAMIC_DB_CONFIG')->dropIfExists($table_name);
                (Helper::is_admin_login()) ?
                \DB::table('table_metas')->where('table_name', $table_name)->delete() : Helper::interrupt(620);
                return Response::respond(613, 'dropped table successfully');
                $task = 'drop';
            }
        }
        if (isset($payload['params'][0]['params'][0]['where'])) {
            if ($payload['params'][0]['params'][0]['where'] == true) {
                $where = $payload['params'][0]['params'][0]['where'];
                $where = str_replace(',', "','", $where);
                $where = "'".$where."'";
                $destroy_query = $destroy_query.'->where('.$where.')';
                $task = 'failed';
            }
        }
        $element = 'row';
        if (isset($payload['params'][0]['params'][0]['truncate'])) {
            if ($payload['params'][0]['params'][0]['truncate'] == true) {
                $destroy_query = $destroy_query.'->truncate()';
                $tasked = 'truncated';
                $task = 'truncate';
            }
        } elseif (isset($payload['params'][0]['params'][0]['delete'])) {
            if ($payload['params'][0]['params'][0]['delete'] == true) {
                $destroy_query = $destroy_query.'->delete()';
                $tasked = 'deleted';
                $task = 'delete';
            }
        } else {
            Helper::interrupt(615);
        }
        $destroy_query = $destroy_query.';';
        $result = eval('return'.$destroy_query);
        if ($result == false && $result != null) {
            Helper::interrupt(614, 'could not '.$task.' '.$element);
        }
        return Response::respond(636, 'The table or field has been '.$task);
    }
    /**
     * query a table.
     *
     * @param array $payload payload from request
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param string $resource
     */
    public function db_query($payload)
    {
        $service_name = $payload['service_name'];
        $connector = $this->_connector($payload);
        $db = \DB::connection('DYNAMIC_DB_CONFIG');
        $results = [];
        //check if table name is set
        if (isset($payload['params']['table'][0])) {
            if (!\Schema::connection('DYNAMIC_DB_CONFIG')->
            hasTable($service_name.'_'.$payload['params']['table'][0])) {
                return Helper::interrupt(634);
            }
            if ($payload['user_id'] !== '') {
                $user_id = $payload['user_id'];
                $base_query = '$db->table("'.$service_name.'_'.$payload['params']['table'][0].'")'
                .'->where("devless_user_id",'.$user_id.')';
            } else {
                $base_query = '$db->table("'.$service_name.'_'.$payload['params']['table'][0].'")';
            }
            $table_name = $service_name.'_'.$payload['params']['table'][0];
            $complete_query = $base_query;
            (isset($payload['params']['offset'])) ?
            $complete_query = $complete_query
            .'->skip('.$payload['params']['offset'][0].')' :
            false;
            (isset($payload['params']['size'])) ?
            $complete_query = $complete_query
            .'->take('.$payload['params']['size'][0].')' :
            $complete_query = $base_query;
            (isset($payload['params']['related'])) ? $queried_table_list =
            $payload['params']['related'] : false;
            unset($payload['params']['related']);
            $related = [];
            if (isset($payload['params']['orderBy'])) {
                $order_by = $payload['params']['orderBy'];
                $complete_query = $complete_query
                .'->orderBy("'.$payload['params']['orderBy'][0].'" )';
                unset($payload['params']['orderBy']);
            }
            unset(
                $payload['params']['table'],
                $payload['params']['size'],
                $payload['params']['offset']
            );
            
            //finally loop over remaining query params (where)
            foreach ($payload['params'] as $key => $query) {
                foreach ($query as $one) {
                    //prepare query for order and where
                    if (isset($this->query_params[$key])) {
                        $query_params = explode(',', $one);
                        if (isset($query_params[1], $query_params[0])) {
                            $complete_query = $complete_query.
                            '->'.$this->query_params[$key].'("'.$query_params[0].
                            '","'.$query_params[1].'")';
                        } else {
                            Helper::interrupt(612);
                        }
                    } else {
                        Helper::interrupt(610);
                    }
                }
            }
            if (isset($queried_table_list)) {
                $related = function ($results) use ($queried_table_list, $service_name, $table_name, $payload) {
                    return $this->_get_related_data(
                        $payload,
                        $results,
                        $table_name,
                        $queried_table_list
                    );
                };
                $endOutput = [];
                $complete_query = $complete_query.'
                    ->chunk(100, function($results) use(&$endOutput, $related) {
                        $endOutput =  $related($results);
                    });';
            } else {
                $complete_query = 'return '.$complete_query.'->get();';
            }
            $count = $db->table($table_name)->count();
            $query_output = eval($complete_query);
            $results['properties']['count'] = $count;
            $results['results'] = (isset($queried_table_list))? $endOutput : $query_output;
            return Response::respond(625, null, $results);
        } else {
            Helper::interrupt(611);
        }
    }
    /**
     * Create a service table.
     *
     * @param array $payload
     *
     * @return true
     */
    public function create_schema($payload)
    {
        $service_name = $payload['service_name'];
        //connectors mysql pgsql sqlsrv sqlite
         $this->_connector($payload);
         
        //dynamically create columns with schema builder
        $db_type = $this->db_types;
        $table_meta_data = [];
        $new_payload = $payload['params'][0];
        $new_payload['id'] = $payload['id'];
        $table_name = $service_name.'_'.$new_payload['name'];
        if (!\Schema::connection('DYNAMIC_DB_CONFIG')->
        hasTable($service_name.'_'.$table_name)) {
            \Schema::connection('DYNAMIC_DB_CONFIG')->
            create($table_name, function (Blueprint
        $table) use ($new_payload, $db_type, $service_name) {
                //default field
                $table->increments('id');
                $table->integer('devless_user_id');
                
                //generate remaining fields
                foreach ($new_payload['field'] as $field) {
                    $field['ref_table'] = $service_name.'_'.$field['ref_table'];
                    $field['field_type'] = strtolower($field['field_type']);
                    //checks if fieldType and references exist
                    $this->field_type_exist($field);
                    //generate columns
                    $this->column_generator($field, $table, $db_type);
                }
                //store table_meta details
            });
            $this->_set_table_meta($service_name, $new_payload);
            return Response::respond(606);
        } else {
            Helper::interrupt(603, $table_name.' table already exist');
        }
    }
    /**
     * check if field exist.
     *
     * @param column fields (array) $field
     *
     * @return true
     *
     * @internal param table_name $table_name
     */
    public function field_type_exist($field)
    {
        //check if soft data type has equivalent db type
        if (!isset($this->db_types[$field['field_type']])) {
            Helper::interrupt(600, $field['field_type'].' does not exist');
        }
        if (strtolower($field['field_type']) == 'reference') {
            if (!\Schema::connection('DYNAMIC_DB_CONFIG')->
            hasTable($field['ref_table'])) {
                //
                     Helper::interrupt(601, 'referenced table '
                .$field['ref_table'].' does not exist');
            }
        }
    }
    /**
     * check column constraints.
     *
     * @param column fields (array) $field
     *
     * @return int
     */
    public function check_column_constraints($field)
    {
        //create column with default and reference
        if ($field['field_type'] == 'reference' && $field['default'] !== null) {
            return 4;
        } elseif ($field['field_type'] == 'reference' && $field['default'] == null) {
            return 3;
        } elseif ($field['field_type'] != 'reference' && $field['default'] != null) {
            return 2;
        }
        if (($field['field_type'] !== 'reference' && $field['default'] == null)) {
            return 1;
        } else {
            Helper::interrupt(602, 'Database schema could not be created');
        }
    }
    /**
     * generate column data query .
     *
     * @param array  $field
     * @param object $table
     * @param $db_type
     *
     * @return object
     */
    public function column_generator($field, $table, $db_type)
    {
        $column_type = $this->check_column_constraints($field);
        $unique = '';
        if ($field['is_unique'] == 'true') {
            $unique = 'unique';
        }
        if ($column_type == 4) {
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
            ->unsigned()->$unique();
            $table->foreign($field['ref_table'].'_id')->references('id')
            ->on($field['ref_table'])->onDelete('cascade');
        } elseif ($column_type == 3) {
            $table->$db_type[$field['field_type']]($field['ref_table'].'_id')
            ->unsigned()->$unique();
            $table->foreign($field['ref_table'].'_id')->references('id')
            ->on($field['ref_table'])->default($field['default'])
            ->onDelete('cascade');
        } elseif ($column_type == 2) {
            $table->$db_type[$field['field_type']]
            ($field['name'])->default($field['default'])->onDelete('cascade')
            ->$unique();
        } elseif ($column_type == 1) {
            $table->$db_type[$field['field_type']]
            ($field['name'])->onDelete('cascade')->$unique();
        } else {
            Helper::interrupt(
                602,
                'For some reason database schema could not be created'
            );
        }
    }
    /**
     * Devless database connection socket.
     *
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param string $charset
     * @param string $prefix
     * @param string $collation
     */
    public function db_socket(
        $driver,
        $host,
        $database,
        $username,
        $password,
        $charset = 'utf8',
        $prefix = '',
        $collation = 'utf8_unicode_ci'
    ) {
        if ($driver == 'sqlite') {
            $database = database_path('devless-rec.sqlite3');
        }
        $conn = [
        'driver'    => $driver,
        'host'      => $host,
        'database'  => $database,
        'username'  => $username,
        'password'  => $password,
        'charset'   => $charset,
        'prefix'    => $prefix,
        ];
        if ($driver == 'mysql') {
            $conn['collation'] = $collation;
        }
        
        
        \Config::set('database.connections.DYNAMIC_DB_CONFIG', $conn);
    }
    /**
     * access different database connections.
     *
     * @param $connector_params
     *
     * @return bool
     *
     * @internal param Request $payload parameters
     */
    private function _connector($connector_params)
    {
        $driver = $connector_params['driver'];
        
        //get current database else connect to remote
        if ($driver == 'default'|| $driver == "") {
            $default_database = config('database.default');
            $default_connector = config('database.connections.'.$default_database);
            $driver = $default_connector['driver'];
            if (isset($default_connector['hostname'])) {
                $hostname = $default_connector['hostname'];
            } else {
                $hostname = (isset($default_connector['host'])) ? $default_connector['host'] : false;
            }
            $username = (isset($default_connector['username'])) ? $default_connector['username'] : false;
            $password = (isset($default_connector['password'])) ? $default_connector['password'] : false;
            $database = $default_connector['database'];
        } else {
            $driver = $connector_params['driver'];
            $hostname = $connector_params['hostname'];
            $database = $connector_params['database'];
            $username = $connector_params['username'];
            $password = $connector_params['password'];
        }
        $this->db_socket($driver, $hostname, $database, $username, $password);
        return true;
    }
    /**
     * get related tables
     * @param $payload
     * @param $results
     * @param $primaryTable
     * @return array
     */
    private function _get_related_data($payload, $results, $primaryTable, $tables)
    {
        $serviceTables = $this->_get_all_service_tables($payload);
        $tables = (in_array("*", $tables))?
                $this->_get_all_related_tables($primaryTable) : $tables;
        $output = [];
        $service = $payload['service_name'];
        //loop over list of tables check if exist
        foreach ($results as $eachResult) {
                   $eachResult->related = [];
                   array_walk($tables, function ($table) use ($eachResult, &$output, $service) {
                       $refField = $service.'_'.$table.'_id';
                       $referenceId = (isset($eachResult->$refField))? $eachResult->$refField:
                                    Helper::interrupt(640);
                       $relatedData = \DB::table($service.'_'.$table)
                           ->where('id', $referenceId)
                           ->get();
                       $eachResult->related[$table] = $relatedData;
                   });
                    array_push($output, $eachResult);
        }
        return $output;
    }
    /**
     *Get all related tables for a service.
     *@param $stableName
     *@return array
     */
    private function _get_all_related_tables($tableName)
    {
        $relatedTables = [];
        $schema = $this->get_tableMeta($tableName);
        array_walk($schema['schema']['field'], function ($field)
 use ($tableName, &$relatedTables) {
            if ($field['field_type'] == 'reference') {
                array_push($relatedTables, $field['ref_table']);
            }
        });
        return $relatedTables;
    }
    private function _get_all_service_tables($payload)
    {
        $serviceId = $payload['id'];
        $tables = \DB::table('table_metas')
                ->where('service_id', $serviceId)->get();
        return $tables;
    }
    /**
     *Set table meta.
     *@param $service_name
     *@param $schema
     *@return array
     *
     */
    private function _set_table_meta($service_name, $schema)
    {
        \DB::table('table_metas')->insert(['schema' => json_encode($schema),
                    'table_name' => $service_name.'_'.$schema['name'], 'service_id' => $schema['id'], ]);
        return true;
    }
    /**
     *Get table meta.
     * @param $table_name
     * @return array
     *
     */
    public function get_tableMeta($table_name)
    {
        $tableMeta = \DB::table('table_metas')->
        where('table_name', $table_name)->first();
        $tableMeta = json_decode(json_encode($tableMeta), true);
        $tableMeta['schema'] = json_decode($tableMeta['schema'], true);
        return $tableMeta;
    }
    /**
     * Check if a connection can be made to database.
     *
     * @param array $connection_params (hostname,username,password,driver,)
     *
     * @return bool
     */
    public function check_db_connection(array $connection_params)
    {
        $connector = $this->_connector($connection_params);
        return true;
    }
    /**
     * add user id to payload.
     *
     * @param $db_action
     * @param $payload
     *
     * @return mixed $payload || boolean
     */
    private function set_auth_id_if_required($db_action, $payload)
    {
        $service = new Service();
        $access_type = $payload['resource_access_right'];
        $access_state = $service
            ->check_resource_access_right_type($access_type[$db_action]);
        $user_cred = Helper::get_authenticated_user_cred($access_state);
        $payload['user_id'] = $user_cred['id'];
        return $payload;
    }
    /**
     * validate entry data against schema field type.
     *
     * @param string $table_name
     * @param $service_name
     * @param $table_data
     * @param bool $check_password
     *
     * @return bool
     *
     * @internal param string $service_id
     * @internal param array $field_names
     */
    private function _validate_fields(
        $table_name,
        $service_name,
        $table_data,
        $check_password = false
    ) {
        $table_meta = $this->get_tableMeta($service_name.'_'.$table_name);
        $schema = $table_meta['schema'];
        $hit = 0;
        $count = 0;
        foreach ($table_data as $field_unit) {
            foreach ($field_unit as $field => $field_value) {
                foreach ($schema['field'] as $fields) {
                    if ($fields['name'] == $field) {
                        //pass field_type and value to validate
                        $err_msg =
                        Helper::field_check(
                            $field_value,
                            $fields['field_type']
                        );
                        if ($check_password == true &&
                        strtolower($fields['field_type']) == 'password') {
                            $table_data[$count]['password'] =
                            Helper::password_hash($table_data[$count]['password']);
                        }
                        if (is_object($err_msg) == true) {
                            Helper::interrupt(616, $err_msg);
                        }
                    }
                }
            }
            $count++;
        }
        $hit = 1;
        if ($hit == 0) {
            Helper::interrupt(617);
        }
        if ($check_password == 'true') {
            return $table_data;
        } else {
            return true;
        }
    }
}
