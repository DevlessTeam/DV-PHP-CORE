<?php

namespace Devless\Schema;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Helpers\Response as Response;
use App\Http\Controllers\ServiceController as Service;

trait destroyData
{
    use deleteParamList;
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
        $db = $this->connect_to_db($payload);
        //check if table name is set
        $service_name = $payload['service_name'];
        $table = $payload['params'][0]['name'];
        $param_list = $payload['params'][0]['params'][0];
        $task = $tasked = null;
          //remove service appendage from service
        if (($pos = strpos($table, $service_name.'_')) !== false) {
            $tableWithoutService = substr($table, $pos + 1);
        } else {
            $tableWithoutService = $table;
        }
        $table_name = ($tableWithoutService == $payload['params'][0]['name'])
            ? $service_name.'_'.$tableWithoutService:
            $payload['params'][0]['name'];

        $this->check_table_existence('', $table_name);
        

        $destroy_base_query = '$db->table("'.$table_name.'")';
        $destroy_query = $this->check_userbased_destroy($payload, $destroy_base_query);
        $element = 'row';
        
        $output = $this->drop_table($param_list, $table_name, $task, $tasked);
        
        if ($tasked =='dropped') {
            return $output;
        }
        $this->set_id_of_record_to_delete($param_list, $destroy_query, $task);
        $this->truncate_table($param_list, $destroy_query, $task, $tasked);
        $this->delete_record_from_table($param_list, $destroy_query, $task, $tasked);
        
        $result = eval('return '.$destroy_query.';');

        (is_object($result))?Helper::interrupt(615):false;

        return $this->response_from_delete_action($result, $task);
    }

    private function response_from_delete_action($result, $task)
    {
        $element = 'row';
        if (($result == 0 && $task != "truncate")) {
            Helper::interrupt(614, 'could not '.$task.' '.$element);
        }

        return Response::respond(636);
    }

    private function check_userbased_destroy($payload, $base_query)
    {
        if ($payload['user_id'] !== '') {
            $user_id = $payload['user_id'];
            return $base_query.'->where("devless_user_id",'.$user_id.')';
        }
        return $base_query;
    }
}
