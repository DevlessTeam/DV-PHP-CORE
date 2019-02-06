<?php

namespace Devless\Schema;

use App\Helpers\Helper;
use App\Http\Controllers\ServiceController as Service;

trait relation
{
    /**
     * get related tables.
     *
     * @param $payload
     * @param $results
     * @param $primaryTable
     *
     * @return array
     */
    private function _get_related_data($payload, $results, $primaryTable, $tables)
    {
        require_once config('devless')['system_class'];

        $serviceTables = $this->_get_all_service_tables($payload);
        $tables = (in_array('*', $tables)) ?
        $this->_get_all_related_tables($primaryTable) : $tables;
        $output = [];
        $service = $payload['service_name'];
        //loop over list of tables check if exist
        $systemClass = new \devless();
        
        $relIds = $this->get_all_related_ids($results, $service, $tables);
        $allRelated = $this->get_all_related_data($relIds);
        
        foreach ($results as $eachResult) {
            $eachResult->related = [];
            array_walk(
                $tables,
                function ($table) use ($eachResult, &$output, $service, $systemClass, $allRelated) {
                    $refTable = ($table != '_devless_users') ? $service . '_' . $table : 'users';
                    $refField = $refTable . '_id';
                    if ($eachResult->$refField == null) {return;}
                    $referenceId = (isset($eachResult->$refField)) ? $eachResult->$refField :
                    Helper::interrupt(640);
                    $eachResult->related[$table] = [collect($allRelated[$refTable])->where('id', $referenceId)->first()]; 
                }
            );
            array_push($output, $eachResult);
        }

        return $output;
    }

    private function get_all_related_data($relIds) 
    {
        $relatedData = [];
        foreach ($relIds as $table => $ids) {
            if($table == 'users') {
                try {
                    $userData = \DB::table('users')->whereIn('users.id', $ids)
                     ->join('devless_user_profile', 'users.id', '=', 'devless_user_profile.users_id')->get(); 

                } catch(\Exception $e) {
                    dd($e);
                }
                $relatedData['users'] = collect($userData)->map(function ($item)  {
                    return collect($item)->except(['password', 'session_token', 'session_time', 'tags', 'settings', 'payment_token']);
                });
            } else {
                $relatedData[$table] = \DB::table($table)->whereIn('id', $ids)->get(); 
            }
        }
        return $relatedData;
    }

    /**
     *Get all related ids for a table.
     *
     *@param $results
     *@param $service
     *@param $tableName
     *
     *@return array
     */
    private function get_all_related_ids($results, $service, $tables)
    {
        $relationIds = [];
        // die(var_dump($results));
        foreach ($tables as $table) {
            $relationKey = ($table == '_devless_users') ? 'users' : $service . '_' . $table ;
            $relIds = collect($results)->map(function ($item) use ($service, $table, $relationKey) {
                return $item->{$relationKey.'_id'};
            });
            $relationIds[$relationKey] = $relIds;
        }
        return $relationIds;

    }
    /**
     *Get all related tables for a service.
     *
     *@param $tableName
     *
     *@return array
     */
    private function _get_all_related_tables($tableName)
    {
        $relatedTables = [];
        $schema = $this->get_tableMeta($tableName);
        array_walk(
            $schema['schema']['field'],
            function ($field) use ($tableName, &$relatedTables) {
                if ($field['field_type'] == 'reference') {
                    array_push($relatedTables, $field['ref_table']);
                }
            }
        );

        return $relatedTables;
    }
    /**
     *Get all service tables.
     *
     *@param $stableName
     *
     *@return array
     */
    private function _get_all_service_tables($payload)
    {
        $serviceId = $payload['id'];
        $tables = \DB::table('table_metas')
            ->where('service_id', $serviceId)->get();

        return $tables;
    }
}
