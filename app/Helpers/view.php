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
    $assetPath = url('/').'/service_views/'.$serviceName.'/assets/'.$partialAssetPath;
        
    return $assetPath;
}
