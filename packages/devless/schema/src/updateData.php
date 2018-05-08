<?php

namespace Devless\Schema;

use App\Helpers\Helper;
use App\Helpers\Response as Response;

trait updateData
{
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
        $db = $this->connect_to_db($payload);
        $service_name = $payload['service_name'];
        $params = $payload['params'][0];
        $update_ACL = $payload['resource_access_right']['update'];
        if (!isset(
            $params['name'],
            $params['params'][0]['where'],
            $params['params'][0]['data']
        )) {
            Helper::interrupt(614);
        }

            $this->check_table_existence($service_name, $params['name']);
            $table_name = $service_name.'_'.$payload['params'][0]['name'];

            
            $data = $params['params'][0]['data'];
            $where_clause = $this->get_update_where_clause($payload);

        if (isset($data[0]['id'])) {
            unset($data[0]['id']);
        }
            $data = $this->_validate_fields($payload['params'][0]['name'], $service_name, $data, true);
       
        if ($update_ACL == 1) {
            $outcome = $db->table($table_name)->where($where_clause[0], '=', $where_clause[1])->update($data[0]);
        } else {
            $outcome = $db->table($table_name)->where($where_clause[0], '=', $where_clause[1])->where('devless_user_id', $payload['user_id'])->update($data[0]);
        };
            
            return $this->update_record_response($outcome, $params['name']);
    }

    private function get_update_where_clause($payload)
    {
        $where = $payload['params'][0]['params'][0]['where'];
        $explosion = explode(',', $where);
        return $explosion;
    }

    private function update_record_response($outcome, $table_name)
    {
        if ($outcome) {
            return Response::respond(
                619,
                'table '.$table_name.' updated successfuly'
            );
        }
        Helper::interrupt(629, 'Table '.$table_name.' could not be updated');
    }
}
