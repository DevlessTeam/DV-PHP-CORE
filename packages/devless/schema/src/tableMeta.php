<?php

namespace Devless\Schema;

use App\Helpers\Helper;

trait tableMeta
{
    /**
     *Set table meta.
     *
     *@param $service_name
     *@param $schema
     *
     *@return array
     */
    public function set_table_meta($service_name, $schema)
    {
        \DB::table('table_metas')->insert(
            ['schema' => json_encode($schema),
            'table_name' => $service_name.'_'.$schema['name'], 'service_id' => $schema['id'], ]
        );

        return true;
    }

    /**
     * Update table meta.
     *
     * @param $service_name
     * @param $schema
     *
     * @return bool
     */
    public function update_table_meta($service_name, $tableName, $schema)
    {
        if (\DB::table('table_metas')->where('table_name', $service_name.'_'.$tableName)->update(['schema' => json_encode($schema['schema']), 'table_name' => $schema['table_name']])
        ) {
            return true;
        }

        return false;
    }
    /**
     *Get table meta.
     *
     * @param $table_name
     *
     * @return array
     */
    public function get_tableMeta($table_name)
    {
        $tableMeta = \DB::table('table_metas')->
        where('table_name', $table_name)->first();
        $tableMeta = json_decode(json_encode($tableMeta), true);
        $tableMeta['schema'] = json_decode($tableMeta['schema'], true);

        return $tableMeta;
    }

    /**
     * check if field exist.
     *
     * @param column fields (array) $field
     *
     * @return true
     *
     * @internal param table_name $table_name
     */
    public function field_type_exist($field)
    {

        //check if soft data type has equivalent db type
        if (!isset($this->db_types[$field['field_type']])) {
            Helper::interrupt(600, $field['field_type'].' does not exist');
        }
        if (strtolower($field['field_type']) == 'reference') {
            if (!\Schema::connection('DYNAMIC_DB_CONFIG')->hasTable($field['ref_table'])
            ) {
                Helper::interrupt(
                    601, 'referenced table '
                    .$field['ref_table'].' does not exist'
                );
            }
        }
    }
}
