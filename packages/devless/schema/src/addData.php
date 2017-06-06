<?php

namespace Devless\Schema;

use App\Helpers\Helper;
use App\Helpers\Response as Response;

trait addData
{
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
        $service_name = $payload['service_name'];
        $table_name = $payload['params'][0]['name'];
        $db = $this->connect_to_db($payload);

        $this->validate_payload($payload);

        if ($this->add_data_to_db($payload, $service_name, $table_name, $db)){
            return $this->add_record_response($table_name);
        }
    }

    private function add_data_to_db($payload, $service_name, $table, $db)
    {
        foreach ($payload['params'] as $table) {
            $this->check_table_existence($service_name, $table['name']);

            $table_data = $this->_validate_fields(
                $table['name'],
                $service_name,
                $table['field'],
                true
            );

            //assigning autheticated user id
            $table_data[0]['devless_user_id'] = $payload['user_id'];
            $output = $db->table($service_name.'_'.$table['name'])->insert($table_data);
        }
        return $output;
    }    
   
    private function add_record_response($table_name)
    {
        return Response::respond(609, 'Data has been added to '.$table_name
                .' table successfully');
    }

    private function validate_payload($payload)
    {
        (isset($payload['params'][0]['name']) && count($payload['params'][0]['name']) > 0
            && gettype($payload['params'][0]['field']) == 'array' || isset($payload['params'][0]['field'][0])) ? true :
            Helper::interrupt(641);
    }
}
