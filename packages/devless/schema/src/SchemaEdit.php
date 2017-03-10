<?php
/**
 * Created by PhpStorm.
 * User: eddymens
 * Date: 09/03/2017
 * Time: 11:09 PM
 */

namespace Devless\Schema;


class SchemaEdit
{

    public function tableName($serviceName, $oldName, $newName)
    {
        $tableName = $serviceName.'_'.$oldName;
        $dbHandler = new DbHandler();
        $tableMeta = $dbHandler->get_tableMeta($tableName);
        if(!$tableMeta){return false;}
        \Schema::rename($tableName, $serviceName.'_'.$newName);
        $tableMeta['table_name'] = $serviceName.'_'.$newName;
        $tableMeta['schema']['name'] = $newName;
        var_dump($dbHandler->update_table_meta($serviceName, $oldName, $tableMeta));
        return true;


    }

    public function tableDesc($serviceName, $tableName, $newDesc)
    {
        $compTableName = $serviceName.'_'.$tableName;
        $dbHandler = new DbHandler();
        if($tableMeta = $dbHandler->get_tableMeta($compTableName)){
            $tableMeta['schema']['description'] = $newDesc;
            $dbHandler->update_table_meta($serviceName, $tableName, $tableMeta);
            return false;
        }
        return false;
    }

    public function fieldName($newName)
    {
        return;
    }

    public function fieldType($newType)
    {
        return;
    }

    public function delField($serviceName, $tableName, $FieldName)
    {

    }
}