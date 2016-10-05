<?php

namespace App\Helpers;

use App\Http\Controllers\ServiceController;
use DB;
use Hash;
use Session;
use Devless\SDK\SDK;
use App\User as user;
use Alchemy\Zippy\Zippy;
use App\Helpers\DataStore;
use Devless\Schema\DbHandler as DvSchema;
use Symfony\Component\VarDumper\Cloner\Data;
use App\Helpers\Jwt as jwt;
/*
* @author Eddymens <eddymens@devless.io
*/

class DevlessHelper extends Helper
{

    /**
     * set paramters for notification plate
     *
     * @param type $message
     * @param type|string $message_color
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

    /**
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

    /** Get all service attributes
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
     * Delete table is exists
     * @param $serviceName
     * @param $tableName
     * @return bool
     */
    public static function purge_table($serviceName, $tableName)
    {
        $service = new ServiceController();
        return DataStore::service($serviceName, $tableName, $service)->drop()? true: false;

    }


    /**
     * convert string to json
     * @param $incomingArray
     * @return string
     * @internal param $incommingArray
     * @internal param $service_components
     */
    public static function convert_to_json($incomingArray)
    {

        $formatted_json = json_encode($incomingArray, true);

        return $formatted_json;

    }


    /**
     * Zip a folder
     * @param $service_folder_path
     * @param $extension
     * @return string
     */
    public static function zip_folder($service_folder_path, $extension, $delete=false)
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
     * Install service and or package given service path
     * @param $service_path
     * @return bool
     */
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
                $service_table['service_name'] = $service_name[$old_service_id];
                $service_table['driver'] = "default";
                $service_table['schema']['service_id'] = $new_service_id ;
                $service_table['service_id'] = $new_service_id ;
                $service_table['schema']['id'] = $new_service_id;
                $service_table['id'] = $new_service_id;
                $service_table['params'] = [0 =>$service_table['schema']];
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


    /**
     * install views into service_views dir
     * @param $service_name
     * @return bool
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
        $username = (isset($payload['username']))?$payload['username']:'';
        
        $email = (isset($payload['email']))?$payload['email']:'';
        $phone_number = (isset($payload['phone_number']))?$payload['phone_number']:'';
        $existing_users =  \DB::table('users')->orWhere('username', $username)
                ->orWhere('email', $email)
                ->orWhere('phone_number', $phone_number)->get();
         
        if($existing_users != null) {
            return Response::respond(1001,"Seems User already exists");
        }
        
        $user = new User;

        $secret = config('app')['key'];

        $token = $this->auth_fields_handler($payload, $user);

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
     * @return array
     */
    public function get_profile()
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
     * @param $payload
     * @return alphanum
     * @internal param type $request
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
           $correct_password = 
                   (Helper::compare_hash($password, $user_data->password))?true: false ;
            $user_data->session_token = $session_token = md5(uniqid(1, true));

            if ($correct_password && $user_data->save()) {
                $token_payload =
                    [
                        'token' => $session_token,

                    ];

                $prepared_token = $this->set_session_token($token_payload, $user_data->id);

                return $prepared_token;
            } else {
                return false;
            }
        } else {
            return false;
        }


    }

    /**
     * update devless user profile 
     * @param $payload
     * @return bool
     * @internal param type $request
     */
    public function update_profile($payload)
    {
        if ($token = Helper::get_authenticated_user_cred(true)) {
            $user =  new user();

            //unchangeable fields
            $indices = [
                'session_token',
                'status',
                'role'
            ];

            $unset = function ($payload, $index) {
                unset($payload[$index]);
            };

            foreach ($indices as $index) {
                (isset($payload[$index]))? $unset($payload, $index) : false;
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
     * @return bool
     * @internal param type $request
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
     * @param $payload
     * @param $user_id
     * @return bool
     * @internal param type $request
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

    /**
     * check if needed auth fields are satisfied
     * @param $fields
     * @param $user
     * @return mixed
     */
    public function auth_fields_handler($fields, $user)
    {

        $expected_fields =
            [
                'email' => 'email',
                'username' => 'text',
                'password' => 'password',
                'first_name' => 'text',
                'last_name' => 'text',
                'remember_token' => 'text',
                'status'         => 'text'

            ];

        foreach ($fields as $field => $value) {
            $field = strtolower($field);


            if (isset($expected_fields[$field])) {
                $valid = Helper::field_check($value, $expected_fields[$field]);
                if ($valid !== true) {
                    Helper::interrupt(616, 'There is something wrong with your '.$field);
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
     * Check method access type
     * @param $method
     * @param $class
     */
    public static function rpcMethodAccessibility($class, $method)
    {
        $property = $class->getMethod($method);
        $docComment  = $property->getDocComment();

        //check DocComment
        $ACLS =  ['@ACL public', '@ACL private', '@ACL protected'];

        $access_type = function () use ($docComment) {
            (strpos(($docComment), '@ACL private'))? Helper::interrupt(627) :
                (strpos($docComment, '@ACL protected'))? Helper::get_authenticated_user_cred(2) :
                    (strpos($docComment, '@ACL public'))? true : Helper::interrupt(638) ;

        };

        array_filter($ACLS, $access_type);

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

        $replacements = [
            '{{ServiceName}}' => $serviceName,

            '{{MAINDOC}}'=> '/**
 * Created by Devless.
 * Author: '.$username.'
 * Date Created: '.$time.'
 * @Service: '.$serviceName.'
 * @Version: 1.0
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
      * remove stale service assets before installing new one
      * @param type $dir
      * @return boolean
      */
     public static function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
        return true;
    }
    
    /**
     * start post request and close immediately 
     * @param type $url
     * @param type $params
     */
    public static function curl_post_async($url, $params)
   {
        foreach ($params as $key => &$val) {
          if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key.'='.urlencode($val);
        }
        $post_string = implode('&', $post_params);

        $parts=parse_url($url);

        $fp = fsockopen($parts['host'],
            isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);

        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out.= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }
    
     public static function instance_log($url, $token, $purpose)
    {
        $sdk = new SDK($url, $token);
        $instance = DataStore::instanceInfo();
        
        $user = $instance['admin'];
        $app  = $instance['app'];
        $data = [
            'username' => $user->username,
            'email' => $user->email,
            'token' => $app->token,
            'connected_on' => Date(DATE_RFC2822),
            'instance_url' => $_SERVER['HTTP_HOST'],
            'purpose'      => $purpose
        ];
        $status = $sdk->addData('INSTANCE_LOG', 'instance', $data);
        return ($status['status_code'] == 609)? true : false;
        
    }
}
