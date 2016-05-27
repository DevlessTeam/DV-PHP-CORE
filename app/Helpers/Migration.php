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
        $zipped_service_name = $devlessfunc::zip_folder($folder_name)
                                    ://or
        $devlessfunc::flash('failed to create files(630)','error');  
        
        //$outcome=$devlessfunc::download($folder_name);
        
        
        return $zipped_service_name;
    }

    public static function import_service($folder_content)
    {
            //unzip service folder
            //get items from file 
            //move asset folder to resource
            ////get service json
            //insert service record into service table 
            //get id for creating table   
            //create related tables first if not found stop 
            //now create remaining tables 
            //
            //put data and file in right folders  (check if exists)
    }
}
