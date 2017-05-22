<?php

namespace App\Helpers;

trait directoryHelper 
{
	 
      /**
     * Get assets directory for a service
     *
     * @param type $serviceName
     * @param type $assetsSubPath
     * @return string
     */
    public static function assetsDirectory($serviceName, $assetsSubPath)
    {
        $viewsDirectory = config('devless')['views_directory'];
        $assetsDirectoryName = config('devless')['assets_directory_name'];

        return $viewsDirectory.'/'.$serviceName.'/'.$assetsDirectoryName.'/'.$assetsSubPath;
    }


     /**
      * remove stale service assets before installing new one
      * @param type $dir
      * @return boolean
      */
    public static function rmdir_recursive($dir)
    {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }
            if (is_dir("$dir/$file")) {
                self::rmdir_recursive("$dir/$file");
            } else {
                unlink("$dir/$file");
            }
        }
        rmdir($dir);
        return true;
    }



    /**
     * Execute on views creation
     * @param $payload
     * @return bool
     */
    public static function execOnViewsCreation($payload)
    {
        $serviceName = $payload['serviceName'];

        $instanceInfo = DataStore::instanceInfo();

        $username = $instanceInfo['admin']->username;
        $files = ['ActionClass.php'];
        $time = date('jS \of F Y h:i:s A');
        $version = config('devless')['version'];
        $replacements = [
            '{{ServiceName}}' => $serviceName,

            '{{MAINDOC}}'=> '/**
 * Created by Devless.
 * Author: '.$username.'
 * Date Created: '.$time.'
 * Service: '.$serviceName.'
 * Version: '.$version.'
 */
',
        ];

        return self::modifyAssetContent($serviceName, $files, $replacements);
    }

    /**
     * execute scripts after installing and deleting services
     * @param type $payload
     * @return boolean
     */
    public static function execOnServiceStar($payload)
    {
        $service = $payload['serviceName'];
        $serviceMethodPath = config('devless')['views_directory'].$service.'/ActionClass.php';

        (file_exists($serviceMethodPath))?
            require_once $serviceMethodPath : false;

        if (class_exists($service)) {
                $serviceInstance = new $service();
            $results = (isset($payload['delete']) && !isset($payload['install']) && $payload['delete'] == '__onDelete')?
                $serviceInstance->__onDelete() :
                        (isset($payload['install']) && !isset($payload['delete']) && $payload['install'] == '__onImport')?
                            $serviceInstance->__onImport() : false;
            return $results;
        } else {
            return false;
        }

    }


       /**
     * Get and replace file content
     * @param $filePath
     * @param $oldText
     * @param $replacement
     * @return int
     */
    public static function modifyFileContent($filePath, $oldText, $replacement)
    {

        $fileContent = file_get_contents($filePath);

        $fileContent = str_replace($oldText, $replacement, $fileContent);

        return file_put_contents($filePath, $fileContent);

    }


    /**
     * @param $serviceName
     * @param $files
     * @param $replacements
     * @return bool
     */
    public static function modifyAssetContent($serviceName, array $files, array $replacements)
    {

        $forEachFile = function ($fileName) use ($serviceName, $replacements) {

            $filePath = config('devless')['views_directory'].$serviceName.'/'.$fileName;

            foreach ($replacements as $oldContent => $newContent) {
                $state = self::modifyFileContent($filePath, $oldContent, $newContent);
            }
            return $state;
        };

        return array_filter($files, $forEachFile);

    }


    /**
     * Add services to folder
     * @param $service_name
     * @param $service_components
     * @param null $package_name
     * @return string
     */
    public static function add_service_to_folder(
        $service_name,
        $service_components,
        $package_name = null
    ) {

        if ($package_name == null) {
            $package_name  = $service_name;
        }

        $temporal_package_path = storage_path().'/'.$package_name;

        $service_schema_path = $temporal_package_path.'/service.json';

        $new_assets_path = storage_path().'/'.$package_name.'/view_assets/';

        $service_view_path = $new_assets_path.'/'.$service_name;

        $views_directory = config('devless')['views_directory'].$service_name;

        if (!file_exists($temporal_package_path) && !file_exists($service_view_path)) {
            mkdir($temporal_package_path);
            mkdir($new_assets_path);
        }

        if (!file_exists($service_view_path)) {
            mkdir($service_view_path);
        }

        //move asset files to temporal folder
        self::recurse_copy($views_directory, $service_view_path);

        if (!file_exists($service_schema_path)) {
            $fb = fopen($service_schema_path, 'w');
            $fb = fwrite($fb, $service_components);
        }

        //return folder_name
        return $temporal_package_path;

    }



    /**
     * Copy a whole folder
     * @param $src
     * @param $dst
     * @return bool
     */
    public static function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    self::recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return file_exists($dst);
    }

    /**
     * Delete given directory
     * @param $dir
     * @return bool
     */
    public static function deleteDirectory($dir)
    {

        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Get file path from storage
     * @param $filename
     * @return bool|string
     */
    public static function get_file($filename)
    {

        $file_path = storage_path().'/'.$filename;
        if (!file_exists($file_path)) {
            return false;
        }

        return $file_path;
    }

  
    /**
     * Zip a folder
     * @param $service_folder_path
     * @param $extension
     * @return string
     */
    public static function zip_folder($service_folder_path, $extension, $delete = false)
    {
        $dvext = $extension;
        // Load Zippy
        $zippy = Zippy::load();
        //Creates a service_folder_name.pkg
        //that contains a directory "folder" that contains
        //files contained in "service_folder_name" recursively

        $folder_name = basename($service_folder_path);

        $archive = $zippy->create($service_folder_path.'.zip', array(
            $folder_name => $service_folder_path
        ), true);


        rename($service_folder_path.'.zip', $service_folder_path.$dvext);
        ($delete)?self::deleteDirectory($service_folder_path): false;
        return $folder_name.$dvext;

    }


    /**
     * Extract package or services
     * @param string $service_folder_path
     * @param bool $delete_package
     * @return bool|string
     */
    public static function expand_package($service_folder_path, $delete_package = true)
    {
        $zip = new \ZipArchive;

        //convert from srv/pkg to zip
        $new_service_folder_path = preg_replace('"\.srv$"', '.zip', $service_folder_path);
        $new_service_folder_path = preg_replace('"\.pkg$"', '.zip', $service_folder_path);

            (rename($service_folder_path, $new_service_folder_path))? $new_service_folder_path
                :false;

        $res = $zip->open($new_service_folder_path);
        if ($res === true) {
            $zip->extractTo(config('devless')['views_directory']);
            $extract_name = config('devless')['views_directory'].$zip->getNameIndex(0);
            $extract_name = str_replace('service.json', '', $extract_name);
            $zip->close();
            
            self::deleteDirectory($new_service_folder_path);
            ($delete_package)? self::deleteDirectory($service_folder_path):false;
        } else {
            return false;
        }

        return $extract_name;
    }

    
    /**
     * install views into service_views dir
     * @param $service_name
     * @return bool
     */
    public static function install_views($service_name)
    {
        $system_view_directory = config('devless')['views_directory'];
        $service_view_directory = $service_name.'view_assets';
        self::recurse_copy($service_view_directory, $system_view_directory);
        self::deleteDirectory($service_view_directory);

        return true;
    }


}