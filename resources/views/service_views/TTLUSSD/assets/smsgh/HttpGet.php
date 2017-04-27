<?php
/**
 * An HTTP GET request.
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class HttpGet extends HttpRequest {
    
    public function __construct($curlHandle, $path, $accept, array $params = NULL) {
        parent::__construct($curlHandle, $path, $accept, $params);
        $this->httpMethod = "GET";
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $this->httpMethod);
    }
}
