<?php
/**
 * devless config file
 *
 * @author eddymens <eddymens@devless.io>
 */

$url = function(){
  return (isset($_SERVER['SERVER_NAME']))? $_SERVER['SERVER_NAME']: '';  
};

return[
  'views_directory_name' =>'service_views', 
  'assets_route_name' => 'service_views',  
  'assets_directory_name' => 'assets',  
  'views_directory' => base_path().'/resources/views/service_views/',
  
    
];


