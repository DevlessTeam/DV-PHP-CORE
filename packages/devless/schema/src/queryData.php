<?php

namespace Devless\Schema;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Helpers\Response as Response;

trait queryData
{
    use queryParamList;
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
        $db = $this->connect_to_db($payload);
        $queried_results = null;
        $size_count = null;
        $related_fetch = null;
        $results = [];
        //check if table name is set
        if (!isset($payload['params']['table'][0])){Helper::interrupt(611);}

        $this->check_table_existence($service_name, $payload['params']['table'][0]);

        $table_name = $this->devlessTableName($service_name, $payload['params']['table'][0]);

        $base_query = '$db->table("'.$table_name.'")';

         ($payload['user_id'] !== '') ?
        $complete_query = $base_query.'->where("devless_user_id",'.$payload['user_id'].')' : ''; 

        $complete_query = $this->set_query_options(
            $base_query,
            $payload,
            $table_name,
            $size_count,
            $related_fetch,
            $related_fetch
        );
        
        $count = $db->table($table_name)->count();
        
        ($related_fetch)? eval('return '.$complete_query.'
        ->chunk($count, function($results) use (&$queried_results, &$related_fetch) {
             return $queried_results = $related_fetch($results);
        });'):eval('$queried_results = '.$complete_query.'->get();');
        
        return $this->respond_with_query_data($queried_results, $count);
        
    }

    private function set_query_options(&$complete_query, &$payload, $table_name,  &$size_count, $db, &$related_fetch)
    {

        $query_args_list = [
                'related' => [&$complete_query, &$payload, $table_name, &$related_fetch],
                'size' => [&$complete_query, &$payload, &$size_count, &$related_fetch],
        ];

        unset($payload['params']['table']);
        foreach ($payload['params'] as $param_name => $param_value) {
            
           (!isset($this->query_params[$param_name]))?Helper::interrupt(610, "Query parameter $param_name does not exist"):'';
            
            $param_name = $this->query_params[$param_name];

            $query_args = ($param_name != 'related' && $param_name != 'size') ? [&$complete_query, &$payload] : $query_args_list[$param_name];
            
            $this->$param_name(...$query_args);
        }

        return $complete_query;
    }

    private function respond_with_query_data($results, $total_count)
    {
        return Response::respond(625, null, [
            'results' => $results,
            'properties' => ['count' => $total_count, 'current_count' => count($results)]
        ]);
    }

}
