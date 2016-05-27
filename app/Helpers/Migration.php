<?php


namespace App\Helpers;

use App\Helpers\DevlessHelper as DVHelper;
/**
 * Description of Migration
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
        
        ($folder_name)?
        $zipped_service_components = $devlessfunc::zip_folder($folder_name)
                                    ://or
        $devlessfunc::flash('failed to create files(630)','error');  
        
        $outcome=$devlessfunc::download($folder_name);
        
        
        return $outcome;
    }

    public static function import_service($folder_content)
    {
            //unzip service folder
            //get items from file 
            //put data and file in right folders  (check if exists)
    }
}
