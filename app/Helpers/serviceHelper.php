<?php

namespace App\Helpers;

use DB;
use Devless\Schema\DbHandler as DvSchema;

trait serviceHelper
{
    /**
     * get service_component from db.
     *
     * @param $service_name
     *
     * @return service_object
     */
    public static function get_service_components($service_name)
    {
        $service = \DB::table('services')
            ->where('name', $service_name)->first();

        $tables = \DB::table('table_metas')
            ->where('service_id', $service->id)->get();

        $views_folder = $service_name;

        $service_components['service'] = $service;
        $service_components['tables'] = $tables;
        $service_components['views_folder'] = $views_folder;

        $service_components = self::convert_to_json($service_components);

        return $service_components;
    }

    /**
 * Get all service attributes
     * @return string
     */
    public static function get_all_services()
    {
        $services = \DB::table('services')->get();
        $tables = \DB::table('table_metas')->get();

        $services_components['service'] = $services;
        $services_components['tables'] = $tables;

        $services_components = self::convert_to_json($services_components);

        return $services_components;
    }

    /**
     * Install service and or package given service path.
     *
     * @param $service_path
     *
     * @return bool
     */
    public static function install_service($service_path)
    {
        $builder = new DvSchema();
        $service_file_path = $service_path.'service.json';
        $service_file_path = preg_replace('"\.srv"', '', $service_file_path);
        $service_file_path = preg_replace('"\.pkg"', '', $service_file_path);
        $fh = fopen($service_file_path, 'r');
        $service_json = fread($fh, filesize($service_file_path));
        fclose($fh);

        $service_object = json_decode($service_json, true);
        $service_id = [];
        $service_name = [];
        $service_id_map = [];
        $install_services = function ($service) use (&$service_id, &$service_name, &$service_id_map) {
            if (count(\DB::table('services')->where('name', $service['name'])->get()) != 0) {
                return false;
            }
            $old_service_id = $service['id'];
            $service_name[$old_service_id] = $service['name'];
            unset($service['id']);
            if(!isset($service['raw_script']))
            {
                $service['raw_script'] = $service['script']
            }
            \DB::table('services')->insert($service);
            $last_service = \DB::table('services')->orderBy('id', 'desc')->first();
            $service_id_map[$old_service_id] = $last_service->id;
        };
        if (!isset($service_object['service'][0])) {
            $install_services($service_object['service']);
        } else {
            foreach ($service_object['service'] as $service) {
                $install_services($service);
            }
        }
        //get meta from service_table
        $table_meta_install = function ($service_table) use (
            &$service_id_map,
            &$builder,
            &$service_name
        ) {
            if (sizeof($service_table) !== 0) {
                if (\Schema::hasTable($service_table['table_name'])) {
                    return false;
                }
                $old_service_id = $service_table['service_id'];
                $new_service_id = $service_id_map[$old_service_id];
                $service_table['schema'] = json_decode($service_table['schema'], true);
                $service_table['service_name'] = $service_name[$old_service_id];
                $service_table['driver'] = 'default';
                $service_table['schema']['service_id'] = $new_service_id;
                $service_table['service_id'] = $new_service_id;
                $service_table['schema']['id'] = $new_service_id;
                $service_table['id'] = $new_service_id;
                $service_table['params'] = [0 => $service_table['schema']];
                $builder->create_schema($service_table);
            }
        };
        if (!isset($service_object['tables'][0])) {
            $table_meta_install($service_object['tables']);
        } else {
            foreach ($service_object['tables'] as $service_table) {
                $table_meta_install($service_table);
            }
        }

        unlink($service_file_path);

        return true;
    }
}
