<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 09/03/2017
 * Time: 11:09 PM.
 */

namespace Devless\Schema;

use Illuminate\Database\Schema\Blueprint;

class SchemaEdit
{
    /**
     * Change table name.
     *
     * @param $serviceName
     * @param $oldName
     * @param $newName
     *
     * @return bool
     */
    public function updateTableName($serviceName, $oldName, $newName)
    {
        $dbHandler = new DbHandler();
        $tableName = $dbHandler->devlessTableName($serviceName, $oldName);
        $tableMeta = $dbHandler->get_tableMeta($tableName);
        if (!$tableMeta) {
            return false;
        }
        \Schema::rename($tableName, $dbHandler->devlessTableName($serviceName, $newName));
        $tableMeta['table_name'] = $dbHandler->devlessTableName($serviceName, $newName);
        $tableMeta['schema']['name'] = $newName;
        $dbHandler->update_table_meta($serviceName, $oldName, $tableMeta);

        return true;
    }

    /**
     * Change Table description.
     *
     * @param $serviceName
     * @param $tableName
     * @param $newDesc
     *
     * @return bool
     */
    public function updateTableDesc($serviceName, $tableName, $newDesc)
    {
        $dbHandler = new DbHandler();
        $compTableName = $dbHandler->devlessTableName($serviceName, $tableName);
        if ($tableMeta = $dbHandler->get_tableMeta($compTableName)) {
            $tableMeta['schema']['description'] = $newDesc;
            $dbHandler->update_table_meta($serviceName, $tableName, $tableMeta);

            return true;
        }

        return false;
    }

    /**
     * Change field name.
     *
     * @param $serviceName
     * @param $tableName
     * @param $oldName
     * @param $newName
     *
     * @return bool
     */
    public function updateFieldName($serviceName, $tableName, $oldName, $newName)
    {
        $dbHandler = new DbHandler();
        $compTableName = $dbHandler->devlessTableName($serviceName, $tableName);
        $tableMeta = $dbHandler->get_tableMeta($compTableName);
        \Schema::table($compTableName, function (Blueprint $table) use (
            $oldName,
            $newName,
            $dbHandler,
            $compTableName,
            &$tableMeta
) {
            $count = 0;
            $table->renameColumn($oldName, $newName);
            foreach ($tableMeta['schema']['field'] as $field) {
                if ($field['name'] == $oldName) {
                    $tableMeta['schema']['field'][$count]['name'] = $newName;
                }
                ++$count;
            }

            return true;
        });
        $dbHandler->update_table_meta($serviceName, $tableName, $tableMeta);

        return false;
    }

    /**
     * Add new field.
     *
     * @param $serviceName
     * @param $tableName
     * @param $fieldName
     * @param $fieldType
     *
     * @return bool
     */
    public function addField($serviceName, $tableName, $fieldName, $fieldType)
    {
        if ($fieldType == 'reference') {
            return false;
        }
        $dbHandler = new DbHandler();
        $compTableName = $dbHandler->devlessTableName($serviceName, $tableName);
        $newField = json_decode('{"name":"'.$fieldName.'","field_type":"'.$fieldType.'","ref_table":
        "_devless_users","default":null,"required":false,"validation":false,
        "is_unique":false}');
        $tableMeta = $dbHandler->get_tableMeta($compTableName);
        \Schema::table($compTableName, function (Blueprint $table) use (
            $fieldName,
            $tableName,
            $fieldType,
            $dbHandler,
            &$tableMeta,
            $newField
) {
            $fieldType = $dbHandler->db_types[$fieldType];
            array_push($tableMeta['schema']['field'], $newField);
            $table->$fieldType($fieldName)->nullable();
        });
        $dbHandler->update_table_meta($serviceName, $tableName, $tableMeta);

        return true;
    }

    /**
     * Delete field.
     *
     * @param $serviceName
     * @param $tableName
     * @param $fieldName
     *
     * @return bool
     */
    public function delField($serviceName, $tableName, $fieldName)
    {
        $dbHandler = new DbHandler();
        $compTableName = $dbHandler->devlessTableName($serviceName, $tableName);
        $tableMeta = $dbHandler->get_tableMeta($compTableName);
        if (!$tableMeta['schema']) {
            return false;
        }
        \Schema::table($compTableName, function (Blueprint $table) use ($fieldName, &$tableMeta) {
            $table->dropColumn($fieldName);
            $count = 0;
            foreach ($tableMeta['schema']['field'] as $field) {
                if ($field['name'] == $fieldName) {
                    unset($tableMeta['schema']['field'][$count]);
                }
                ++$count;
            }
        });
        $dbHandler->update_table_meta($serviceName, $tableName, $tableMeta);
    }
}
