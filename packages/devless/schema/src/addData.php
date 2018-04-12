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

        if ($payload = $this->add_data_to_db($payload, $service_name, $table_name, $db)) {
            return $this->add_record_response($table_name, $payload);
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
            for ($i = 0; count($table_data) > $i; $i++) {
                $table_data[$i]['devless_user_id'] = $payload['user_id'];
                    $output = $db->table($service_name.'_'.$table['name'])->insertGetId($table_data[$i]);
            }
        }
        return ['entry_id' => $output];
    }
   
    private function add_record_response($table_name, $payload)
    {
        return Response::respond(
            609,
            'Data has been added to '.$table_name
            .' table successfully',
            $payload
        );
    }

    private function validate_payload($payload)
    {
        (isset($payload['params'][0]['name'])
            &&(gettype($payload['params'][0]['name']) != 'array' || count($payload['params'][0]['name']) > 0)
            && gettype($payload['params'][0]['field']) == 'array' || isset($payload['params'][0]['field'][0])) ? true :
            Helper::interrupt(641);
    }
}
