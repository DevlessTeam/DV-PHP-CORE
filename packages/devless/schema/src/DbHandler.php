<?php
namespace Devless\Schema;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\ServiceController as Service;
class DbHandler
{
    use columns, connector, tableMeta, relation,
    schemaProperties, addData, updateData, destroyData,
    queryData, createTable;
    private $dbActionMethod = [
        'GET' => 'db_query',
        'POST' => 'add_data',
        'PATCH' => 'update',
        'DELETE' => 'destroy',
    ];
    
    public $dbActionAssoc = [
        'GET' => 'query',
        'POST' => 'create',
        'PATCH' => 'update',
        'DELETE' => 'delete',
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
        $this->create_schema($request['resource']);
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
        $user_id = ($db_action != 'query') ? 1 : '';
        $access_type = $payload['resource_access_right'];
        $access_state = $service
            ->check_resource_access_right_type($access_type[$db_action]);
        if ($access_state == true) {
            $user_cred = Helper::get_authenticated_user_cred($access_state);
            $user_id = $user_cred['id'];
        }
        $payload['user_id'] = $user_id;
        return $payload;
    }

    private function connect_to_db($payload)
    {
        $this->_connector($payload);
        return \DB::connection('DYNAMIC_DB_CONFIG');
    }

    private function check_table_existence($service, $table)
    {
        if(strlen($service) == 0 ) {
            $table_to_delete = $table;
        } else { $table_to_delete = $service.'_'.$table; 
        }
        
        if (!\Schema::connection('DYNAMIC_DB_CONFIG')->hasTable($table_to_delete)
        ) {
            Helper::interrupt(634, 'Seems the table `'.$table.'` does not exist');
        }
    }
    /**
     * Get DevLess table name.
     *
     * @param $serviceName
     * @param $tableName
     *
     * @return string
     */
    public function devlessTableName($serviceName, $tableName)
    {
        return $serviceName.'_'.$tableName;
    }
    /**
     * validate incoming  data against schema field type.
     *
     * @param string       $table_name
     * @param $service_name
     * @param $table_data
     * @param bool         $check_password
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
        $count = 0;
        foreach ($table_data as $field_unit) {
            foreach ($field_unit as $field => $field_value) {
                foreach ($schema['field'] as $fields) {
                    if ($fields['name'] == $field) {
                        if ($check_password == true 
                            && strtolower($fields['field_type']) == 'password'
                        ) {
                            $table_data[$count]['password'] =
                                Helper::password_hash($table_data[$count]['password']);
                        }
                        if( strtolower($fields['field_type']) == 'timestamp' ){$field_value = $table_data[$count][$fields['name']] = time();;}
                        if (!Helper::field_check($field_value, $fields['field_type'])) {
                            Helper::interrupt(616, 'The field '.$fields['name'].' cannot  be set to `'.$field_value.'`. It\'s '.$fields['field_type']);
                        }
                        if ($fields['required'] && strlen($field_value) == 0) {
                            Helper::interrupt(616, $field.' cannot be empty');
                        }
                    }
                }
            }
            ++$count;
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