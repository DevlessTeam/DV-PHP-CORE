<?php

/**
 * Minimal representation of the raw HTTP response copied from the curl_exec function
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class HttpResponse {

    private $status;
    private $url;
    private $headers;
    private $body;

    public function __construct($curlHandle, $body) {
        // Get the response code, the last effective url
        $this->status = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        $this->url = curl_getinfo($curlHandle, CURLINFO_EFFECTIVE_URL);
        
        $this->headers = curl_getinfo($curlHandle, CURLINFO_HEADER_OUT);
        $this->body = $body;
    }

    public function getStatus(){
        return $this->status;
    }
    
    public function getHeaders(){
        return $this->headers;
    }
    
    public function getUrl(){
        return $this->url;
    }
    
    public function getBody(){
        return $this->body;
    }
}

