<?php

/**
 * Description of BasciRequestHandler
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class BasicRequestHandler implements RequestHandler {

    public function __construct() {
        
    }

    public function openConnection($curlHandle, $url) {
        // Let us initialize the curl
        curl_setopt($curlHandle, CURLOPT_URL, $url);
    }

    public function prepareConnection($curlHandle, $contentType, $accept) {
        $customHeader = array(
            "Content-type : $contentType",
            "Accept: $accept",
            "Accept-Charset : UTF-8"
        );
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $customHeader);
    }

    public function writeToStream($curlHandle) {
        return curl_exec($curlHandle);
    }

}
