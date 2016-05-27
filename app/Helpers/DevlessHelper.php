<?php

namespace App\Helpers;
use Alchemy\Zippy\Zippy;
use Session;
/* 
*@author Eddymens <eddymens@devless.io
 */
use App\Helpers\Helper as Helper;

class DevlessHelper extends Helper
{
    //
    /**
     * set paramters for notification plate
     * 
     * @param type $message
     * @param type $message_color
     */
    public static function flash($message,$message_color="#736F6F")
    {
        $custom_colors = 
        [
            'error' => '#EA7878',
            'warning' => '#F1D97A',
            'success' => '#7BE454',
            'notification' => '#736F6F',
        ];
        (isset($custom_colors[$message_color]))?$notification_color = 
                $custom_colors[$message_color]
                : $notification_color = $message_color;
                
        session::flash('color', $notification_color);
        session::flash('flash_message', $message);
    }
    
    /*
     * @param $service_name 
     * @return service_object
     */
    public static function get_service_components($service_name)
    {
        $service = \DB::table('services')
                ->where('name',$service_name)->first();
        
        $tables = \DB::table('table_metas')
                ->where('service_id',$service->id)->get();
        
        $views_folder =$service_name;
        
        $service_components['service'] = $service;
        $service_components['tables'] = $tables;
        $service_components['views_folder'] = $views_folder;
        
        $service_components = self::convert_to_json($service_components);
                
        return $service_components;
    }


    public static function convert_to_json($service_components)
    {
            
        $formatted_service_components = json_encode($service_components,true);
        
        return $formatted_service_components;
        
    }
    
    
    public static function zip_folder($service_folder_name)
    {
        
        // Load Zippy
        $zippy = Zippy::load();
                //Creates a service_folder_name.pkg 
                //that contains a directory "folder" that contains
        //files contained in "service_folder_name" recursively
        $archive = $zippy->create($service_folder_name.'.zip', array(
            $service_folder_name => $service_folder_name
        ), true);
        
       rename($service_folder_name.'.zip',$service_folder_name.'.pkg');
       self::deleteDirectory($service_folder_name);
       return $service_folder_name.'.pkg';     

    }
    
    public static function add_service_to_folder($service_name,$service_components)
    {
        $temporal_service_path = $service_name;
        $new_assets_path = $service_name.'/assets';
        $views_directory = config('devless')['views_directory'].$service_name;
        
        mkdir($temporal_service_path);
        mkdir($new_assets_path);
        
        //move asset files to temporal folder
        self::recurse_copy($views_directory, $new_assets_path);
        
        $fb = fopen($service_name.'/service.json','w');
        $fb = fwrite($fb, $service_components);
        
       //return folder_name
        return $temporal_service_path;
            
    }
    
    public static function recurse_copy($src,$dst)
    { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) 
        { 
            if (( $file != '.' ) && ( $file != '..' ))
            { 
                if ( is_dir($src . '/' . $file) )
                { 
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else 
                { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
    
    public static function deleteDirectory($dir) {
        if (!file_exists($dir))
        {
            return true;
        }

        if (!is_dir($dir)) 
        {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) 
        {
            if ($item == '.' || $item == '..')
            {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
            {
                return false;
            }

        }

        return rmdir($dir);
    }
            
}