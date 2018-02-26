<?php
/**
 * devless config file.
 *
 * @author eddymens <eddymens@devless.io>
 */


$devless_payload = [
    'id'           => 0,
    'name' => 'devless',
    'database'     =>  'default',
    'resource_access_right' =>
    '{"query":"0","create":"0","update":"0","delete":"0","schema":"0","script":"0","view":"0"}',
    'script'       => '',
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
    'port'         =>  null,
];

return[
  'devless_profile_schema'=> [
       [
         "name" => "user_profile",
         "description" => "Table for storing DevLess extended profiles",
         "field" => [
           [
             "name" => "devless__devless_users_id",
             "field_type" => "reference",
             "ref_table" => "_devless_users",
             "default" => null,
             "required" => false,
             "validation" => false,
             "is_unique" => true,
           ],
         ],
       ],
     ],
  'assets_route_name'     => 'service_views',
  'assets_directory_name' => 'assets',
  'helpers'               => base_path().'/app/Helpers/',
  'packages_path'         => base_path().'/packages/',
  'system_class'          => base_path().'/packages/devless/systemClass/src/systemClass.php',
  'devless_service'       => $devless_payload,
  'name'                  => 'devless',
  'views_directory_name'  => 'service_views',
  'views_directory'       => base_path().'/resources/views/service_views/',
  'version'               => '1.3.6',
];
