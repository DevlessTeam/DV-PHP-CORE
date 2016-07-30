<?php

/**
 * Include a php file
 *
 * @param array $payload
 * @param string $templateName
 * @return
 */
function DvInclude($payload, $templateName)
{
    $serviceName = $payload['service_name'];
    $viewPath = config('devless')['views_directory'];
    
    include($viewPath.$serviceName.'/'.$templateName);
}

/**
 * Get asset path
 *
 * @param string $payload
 * @param string $assetPath
 * @return string
 */
function DvAssetPath($payload, $partialAssetPath)
{
    $serviceName = $payload['service_name'];
    
    $assetPath = config('devless')['assets_route'].$serviceName.'/'.$partialAssetPath;
        
    return $assetPath;
}

/**
 * allow access to admin only 
 * 
 * @param boolean $message
 */
function DvAdminOnly($message = "Sorry you dont have access to this page")
{
    $helper = app('App\Helpers\Helper');
    $is_admin = $helper->is_admin_login();
    
    ($is_admin)? true : $helper->interrupt(1001,$message);
    
}

function DvNavigate($payload, $pageName)
{
    $pagePath = url('/').'/service/'.$payload['service_name'].'/view/'.$pageName;
    
    return  $pagePath ; 
}
