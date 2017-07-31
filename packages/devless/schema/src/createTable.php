<?php

namespace Devless\Schema;

use App\Helpers\Helper;
use App\Helpers\Response as Response;
use App\Http\Controllers\ServiceController as Service;
use Illuminate\Database\Schema\Blueprint as Blueprint;

trait createTable
{
    /**
     * Create a service table.
     *
     * @param array $payload
     *
     * @return true
     */
    public function create_schema($payload)
    {
        if (!Helper::is_admin_login()) {
            $this->set_auth_id_if_required('schema', $payload);
        }
        $service_name = $payload['service_name'];

        //connectors mysql pgsql sqlsrv sqlite
        $this->_connector($payload);

        //dynamically create columns with schema builder
        $db_type = $this->db_types;
        $new_payload = $payload['params'][0];
        $new_payload['id'] = $payload['id'];
        $table_name = $service_name.'_'.$new_payload['name'];
        if (!\Schema::connection('DYNAMIC_DB_CONFIG')->hasTable($service_name.'_'.$table_name)
        ) {
            \Schema::connection('DYNAMIC_DB_CONFIG')->
            create(
                $table_name, function (Blueprint
                    $table
                ) use (&$new_payload, $db_type, $service_name) {
                    //default field
                    $table->increments('id');
                    $table->integer('devless_user_id');
                    $table->timestamps();   
                    
                    //generate remaining fields
                    $count = 0;
                    foreach ($new_payload['field'] as $field) {
                        $field['ref_table'] = $service_name.'_'.$field['ref_table'];
                        $field['field_type'] = strtolower($field['field_type']);

                        //check if users table is being referenced
                        if ($field['field_type'] == 'reference' 
                            && strpos($field['ref_table'], '_devless_users')== true
                        ) {
                            $field['ref_table'] = 'users';

                            $new_payload['field'][$count]['ref_table'] = '_devless_users';
                            $new_payload['field'][$count]['name'] = 'users_id';
                        }

                        $this->field_type_exist($field);
                        //generate columns
                        $this->column_generator($field, $table, $db_type);
                        ++$count;
                    }
                    //store table_meta details
                }
            );
            $this->set_table_meta($service_name, $new_payload);

            return Response::respond(606);
        } else {
            Helper::interrupt(603, $table_name.' table already exist');
        }
    }
}
