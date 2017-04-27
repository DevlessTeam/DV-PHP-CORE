<?php

/**
 * An HTTP HEAD request.
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class HttpHead extends HttpRequest{  
    
    public function __construct($curlHandle, $path, $accept, array $params = NULL) {
        parent::__construct($curlHandle, $path,  $accept, $params);
        $this->httpMethod = "HEAD";
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $this->httpMethod);
        curl_setopt($curlHandle, CURLOPT_NOBODY, TRUE);
    }
}
