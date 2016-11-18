<?php
/**
 * devless config file.
 *
 * @author eddymens <eddymens@devless.io>
 */

$devless_payload = new stdClass();

$devless_object_keys = [
    'id'           => 0,
    'name' => 'devless', 
    'database'     =>  'default', 
    'resource_access_right' =>
    '{"query":"0","create":"0","update":"0","delete":"0","schema":"0","script":"0","view":"0"}',
    'script'       => 'echo "Surely silence can sometimes be the most eloquent reply.";',
    'method'       => 'POST',
    'params'       =>  '', 
    'public'       =>  '',
    'script_init_vars'    =>  '',
    'active'       =>  '',
    'calls'        =>  '',
    'driver'       =>  '',
    'hostname'     =>  '',
    'username'     =>  '',
    'password'     =>  '',
];

foreach ($devless_object_keys as $key => $value)
{
    $devless_payload->$key = $value;
} 


return[
  'assets_route_name'     => 'service_views',
  'assets_directory_name' => 'assets',  
  'helpers'               => base_path().'/app/Helpers/', 
  'devless_service'       => $devless_payload,  
  'name'                  => 'devless',   
  'views_directory_name'  => 'service_views',
  'views_directory'       => base_path().'/resources/views/service_views/',
  'version'               => '1.0.1',
];



