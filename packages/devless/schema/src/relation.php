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
        $serviceTables = $this->_get_all_service_tables($payload);
        $tables = (in_array('*', $tables)) ?
            $this->_get_all_related_tables($primaryTable) : $tables;
        $output = [];
        $service = $payload['service_name'];
        //loop over list of tables check if exist
        foreach ($results as $eachResult) {
            $eachResult->related = [];
            array_walk(
                $tables, function ($table) use ($eachResult, &$output, $service) {
                    $refTable = ($table != '_devless_users') ? $service.'_'.$table : 'users';
                    $refField = $refTable.'_id';
                    $referenceId = (isset($eachResult->$refField)) ? $eachResult->$refField :
                    Helper::interrupt(640);
                    $relatedData = ($table != '_devless_users') ? \DB::table($refTable)->where('id', $referenceId)
                    ->get() : \DB::table($refTable)->where('id', $referenceId)
                    ->select(
                        'username',
                        'first_name',
                        'last_name',
                        'email',
                        'phone_number',
                        'status',
                        'phone_number',
                        'role'
                    )
                    ->get();
                    $eachResult->related[$table] = $relatedData;
                }
            );
            array_push($output, $eachResult);
        }

        return $output;
    }
    /**
     *Get all related tables for a service.
     *
     *@param $stableName
     *
     *@return array
     */
    private function _get_all_related_tables($tableName)
    {
        $relatedTables = [];
        $schema = $this->get_tableMeta($tableName);
        array_walk(
            $schema['schema']['field'], function ($field) use ($tableName, &$relatedTables) {
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
