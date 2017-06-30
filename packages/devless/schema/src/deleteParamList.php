<?php
namespace Devless\Schema;

use App\Helpers\Helper;
use App\Helpers\Response as Response;

trait deleteParamList
{
    private function drop_table($param_list, $table_name, &$task, &$tasked)
    {    
        if (isset($param_list['drop']) && $param_list['drop'] == true) {
                (Helper::is_admin_login()) ?\Schema::connection('DYNAMIC_DB_CONFIG')->dropIfExists($table_name): Helper::interrupt(620);
                   \DB::table('table_metas')->where('table_name', $table_name)->delete();
                $tasked ='dropped';
                return Response::respond(613, 'dropped table successfully');
                
        }
         $task = 'drop';
    }

    private function set_id_of_record_to_delete($param_list, &$destroy_query, &$task)
    {
        if (isset($param_list['where'])) {
                $where = $param_list['where'];
                $where = str_replace(',', "','", $where);
                $where = "'".$where."'";
                $destroy_query = $destroy_query.'->where('.$where.')';
                $task = 'failed';
        }
    }

    private function truncate_table($param_list, &$destroy_query, &$task, &$tasked)
    {
        if (isset($param_list['truncate']) && $param_list['truncate'] == true) {
                $destroy_query = $destroy_query.'->truncate()';
                $tasked = 'truncated';
                $task = 'truncate';
        }
    }

    private function delete_record_from_table($param_list, &$destroy_query, &$task, &$tasked)
    {
        if (isset($param_list['delete']) && $param_list['delete'] == true) {
                $destroy_query = $destroy_query.'->delete()';
                $tasked = 'deleted';
                $task = 'delete';
        }
    }
}