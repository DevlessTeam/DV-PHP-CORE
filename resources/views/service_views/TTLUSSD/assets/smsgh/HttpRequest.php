<?php

/**
 * Holds data for an HTTP request to be made with the attached HTTP client.
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
abstract class HttpRequest {

    const URLENCODED = "application/x-www-form-urlencoded;charset=UTF-8";

    protected $path;
    protected $contentType;
    protected $body;
    protected $httpMethod;
    protected $curlHandle;
    protected $accept;

    public function __construct($curlHandle, $path, $accept, array $params = NULL) {

        $this->curlHandle = $curlHandle;
        $this->accept = $accept;
        curl_setopt($curlHandle, CURLOPT_HEADER, FALSE);
        curl_setopt($curlHandle, CURLINFO_HEADER_OUT, true);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);

        // Set the path or resource endpoint
        if (isset($path)) {
            $this->path = $path;
        }

        if ($params != NULL && count($params) > 0) {
            // set the request body
            $this->body = http_build_query($params);
            $this->path .= "?" . $this->body;
        }
    }

    public function getPath() {
        return $this->path;
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function getBody() {
        return $this->body;
    }

    public function getHttpMethod() {
        return $this->httpMethod;
    }

    public function getAccept() {
        return $this->accept;
    }

}
