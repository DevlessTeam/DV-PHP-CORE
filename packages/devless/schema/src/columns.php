<?php

namespace Devless\Schema;

use App\Helpers\Helper;

trait columns
{
    /**
     * check column constraints.
     *
     * @param column fields (array) $field
     *
     * @return int
     */
    public function check_column_constraints($field)
    {
        //create column with default and reference
        if ($field['field_type'] == 'reference') {
            return ($field['default'] == null) ? 3 : 4;
        }

        return ($field['default'] == null) ? 1 : 2;
    }
    /**
     * generate column data query .
     *
     * @param array   $field
     * @param object  $table
     * @param $db_type
     *
     * @return object
     */
    private function column_generator($field, $table, $db_type)
    {
        $column_type = $this->check_column_constraints($field);
        
        $unique  = ($field['is_unique'] == 'true')?'unique':'';
        $nullable  = ($field['required'] == 'true')?'':'nullable';

        //for relationships
        if ($column_type == 4) {
            $table->{$db_type[$field['field_type']]}($field['ref_table'].'_id')
                ->unsigned()->$unique();

            $table->foreign($field['ref_table'].'_id')->references('id')
                ->on($field['ref_table'])->onDelete('cascade');

        //relationship with a default
        } elseif ($column_type == 3) {
            // dd($db_type[$field['field_type']]);
            $table->{$db_type[$field['field_type']]}($field['ref_table'].'_id')
                ->unsigned()->$unique();

            $table->foreign($field['ref_table'].'_id')->references('id')
                ->on($field['ref_table'])->default($field['default'])
                ->onDelete('cascade');

        //field with a default
        } elseif ($column_type == 2) {
            $table->{$db_type[$field['field_type']]}
            ($field['name'])->default($field['default'])->onDelete('cascade');

        //field without a default
        } elseif ($column_type == 1) {
            $table->{$db_type[$field['field_type']]}($field['name'])->onDelete('cascade')->$unique()->$nullable();
        } else {
            Helper::interrupt(
                602,
                'For some reason database schema could not be created'
            );
        }
    }
}
