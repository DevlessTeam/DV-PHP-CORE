<?php

namespace App\Helpers;

use Alchemy\Zippy\Zippy;
use Session;
use App\Helpers\JWT as jwt;
use DB;
use Hash;
use App\App;
use App\User as user;
use App\Helpers\Helper as Helper;
use Illuminate\Support\Facades\Storage as Storage;
use App\Http\Controllers\DbController as DvSchema;

/* 
*@author Eddymens <eddymens@devless.io
 */

class DevlessHelper extends Helper
{
    //
    /**
     * set paramters for notification plate
     *
     * @param type $message
     * @param type $message_color
     */
    public static function flash($message, $message_color = "#736F6F")
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
     * get service_component from db 
     * @param $service_name 
     * @return service_object
     */
    public static function get_service_components($service_name)
    {
        $service = \DB::table('services')
                ->where('name', $service_name)->first();
        
        $tables = \DB::table('table_metas')
                ->where('service_id', $service->id)->get();
        
        $views_folder =$service_name;
        
        $service_components['service'] = $service;
        $service_components['tables'] = $tables;
        $service_components['views_folder'] = $views_folder;
        
        $service_components = self::convert_to_json($service_components);
                
        return $service_components;
    }
    
    /*Get all service attributes
     * @return json service_object
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
    
    public static function purge_table($table_name)
    {

        \Schema::dropIfExists($table_name);
        
    }

    public static function convert_to_json($service_components)
    {
            
        $formatted_service_components = json_encode($service_components, true);
        
        return $formatted_service_components;
        
    }
    
    
    public static function zip_folder($service_folder_path, $extension)
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
        self::deleteDirectory($service_folder_path);
        return $folder_name.$dvext;

    }
    
    
    public static function expand_package($service_folder_path, $delete_package)
    {
            $zip = new \ZipArchive;
            
            $service_basename = basename($service_folder_path);
            $state_or_payload = true;
            
            //convert from srv/pkg to zip
            $new_service_folder_path = preg_replace('"\.srv$"', '.zip', $service_folder_path);
            $new_service_folder_path = preg_replace('"\.pkg$"', '.zip', $service_folder_path);
            $state_or_payload =
                 (rename($service_folder_path, $new_service_folder_path))? $new_service_folder_path
                    :false;
            
            $res = $zip->open($new_service_folder_path);
        if ($res === true) {
            $zip->extractTo(config('devless')['views_directory']);
            $zip->close();
                 
            self::deleteDirectory($new_service_folder_path);
            $state_or_payload = ($delete_package)? self::deleteDirectory($service_folder_path):false;
        } else {
            return false;
        }
            
            $folder_name = preg_replace('"\.srv$"', '', $service_basename);
            $exported_folder_path = config('devless')['views_directory'].$folder_name;
            return $exported_folder_path;
    }
    
    
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
    }
    
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
    
    public static function get_file($filename)
    {
        
        $file_path = storage_path().'/'.$filename;
        if (!file_exists($file_path)) {
            return false;
        }

        return $file_path;
    }
    
    public static function install_service($service_path)
    {
        $builder = new DvSchema();
        $service_file_path = $service_path.'/service.json';
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
        
                $old_service_id = $service['id'];
                $service_name[$old_service_id] = $service['name'];
                unset($service['id']);
                \DB::table('services')->insert($service);
                $new_service_id = \DB::getPdo()->lastInsertId();
                $service_id_map[$old_service_id] = $new_service_id ;
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
                $old_service_id = $service_table['service_id'];
                $new_service_id = $service_id_map[$old_service_id];
                $service_table['schema'] = json_decode($service_table['schema'], true);
                $resource = 'schema';
                $service_table['service_name'] = $service_name[$old_service_id];
                $service_table['driver'] = "default";
                $service_table['schema']['service_id'] = $new_service_id ;
                $service_table['service_id'] = $new_service_id ;
                $service_table['schema']['id'] = $new_service_id;
                $service_table['id'] = $new_service_id;
                $service_table['params'] = [0 =>$service_table['schema']];
                $builder->create_schema($resource, $service_table);
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
    
    /*
     * install views into service_views dir
     * @param $service_name
     * @return boolean
     */
    public static function install_views($service_name)
    {
        $service_name = preg_replace('"\.srv$"', '', $service_name);
        $service_name = preg_replace('"\.pkg$"', '', $service_name);
        $system_view_directory = config('devless')['views_directory'];
        $service_view_directory = $system_view_directory.$service_name.'/view_assets';
        self::recurse_copy($service_view_directory, $system_view_directory);
        self::deleteDirectory($service_view_directory);
        
        return true;
    }
    
    /**
     * signup new users onto devless
     * @param type $payload
     * @return alphanum
     */
    public function signup($payload)
    {
        
        $fields = get_defined_vars();
        
        $user = new User;
        
        $secret = config('app')['key'];
        
        $token = $this->auth_fields_handler($fields, $user);
        
        if ($token == false) {
            return $token;
        }
        
          $user->status = 1;
        $user->session_token = $session_token = md5(uniqid(1, true));
           
        //check if either username or email and password is set
        if (!isset($user->password) || ! (isset($user->username) || isset($user->email))) {
            return false;
        }
        
        if ($user->save()) {
            $token_payload =
            [
                'token' => $session_token,
                
            ];
            
            $prepared_token = $this->set_session_token($token_payload, $user->id);
            
            return $prepared_token;
        } else {
            return false;
        }

        
        
        
        
    }
    
    /**
     * get authenticated user details
     * @param type $request
     * @return alphanum
     */
    public function get_profile($payload)
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $db = new DB();
            $user_data = $db::table('users')->where('id', $token['id'])
                  ->select('id', 'username', 'email', 'phone_number', 'first_name', 'last_name')
                  ->first();
                
            
            
            return $user_data;
        }
        
        return false;
    }
    
     /**
     * authenticate and login devless users
     * @param type $request
     * @return alphanum
     */
    public function login($payload)
    {
        
        $fields = get_defined_vars();
        
        $user =  new user();
        $fields = $fields['payload'];
        $secret = config('app')['key'];
        
        foreach ($fields as $field => $value) {
            $field = strtolower($field);
            ${$field} = $value;
        }
        
        if (isset($email,$password)) {
            $user_data = $user::where('email', $email)->first();
        } elseif (isset($username,$password)) {
            $user_data = $user::where('username', $username)->first();
        } else {
            return false;
        }
        if ($user_data !== null) {
            Helper::compare_hash($password, $user_data->password) ;
            $user_data->session_token = $session_token = md5(uniqid(1, true));
           
            if ($user_data->save()) {
                $token_payload =
                [
                    'token' => $session_token,
                    
                ];

                $prepared_token = $this->set_session_token($token_payload, $user_data->id);

                return $prepared_token;
            }
        } else {
            return false;
        }
       
        
    }
    
     /**
     * update user devless project
     * @param type $request
     * @return boolean
     */
    public function update_profile($payload)
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user =  new user();
            
            if (isset($payload['session_token'])) {
                unset($payload['session_token']);
            }
            if (isset($payload['session_time'])) {
                unset($payload['session_time']);
            }
            if (isset($payload['status'])) {
                unset($payload['status']);
            }
            
            if (isset($payload['role'])) {
                unset($payload['role']);
            }
            
            if (isset($payload['password'])) {
                $payload['password'] = Helper::password_hash($payload['password']);
            }
            
            if ($user::where('id', $token['id'])->update($payload)) {
                return true;
            }
        }
        
        return false;
    }
    
     /**
     * delete a devless user
     * @param type $request
     * @return boolean
     */
    public function delete()
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user =  new user();
            if ($user::where('id', $token['id'])->delete()) {
                return true;
            }
        }
        
        return false;
    }
    
    
    public function logout()
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user =  new user();
            if ($user::where('id', $token['id'])->update(['session_token'=> ""])) {
                return true;
            }
        }
            
            return false;
    }






    /**
     * set session token
     * @param type $request
     * @return boolean
     */
    public function set_session_token($payload, $user_id)
    {
        
        $jwt = new jwt();
        $secret = config('app')['key'];
       
        $payload = json_encode($payload);
       
        if (DB::table('users')->where('id', $user_id)->update(['session_time'=>Helper::session_timestamp()])) {
            return $jwt->encode($payload, $secret);
        } else {
            return false;
        }
      
       
    }
    
    public function decode_session_token($payload)
    {
        $jwt = new jwt();
        $secret = config('app')['key'];
       
        if ($user_token == "null") {
            Self::interrupt(633, null, [], true);
        }
        
        return $jwt->decode($payload, $secret, true);
    }
    
    public function auth_fields_handler($fields, $user)
    {
        
            $expected_fields =
        [
            'email' => 'email',
            'username' => 'text',
            'password' => 'password',
            'first_name' => 'text',
            'last_name' => 'text'
            
        ];
        
            foreach ($fields['payload'] as $field => $value) {
                $field = strtolower($field);
            
            
                if (isset($expected_fields[$field])) {
                    $valid = Helper::field_check($value, $expected_fields[$field]);
                    if ($valid !== true) {
                        return Helper::interrupt(616, 'There is something wrong with your '.$field);
                    }
                    if ($field == 'password') {
                        $user->$field = Helper::password_hash($value);
                    } else {
                         $user->$field = $value ;
                    }
                }
            }
           
      
            
            return $user;
        
        
    }
}
