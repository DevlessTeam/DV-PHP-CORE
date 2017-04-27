<?php

/**
 * An HTTP POST request.
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class HttpPost extends HttpRequest {

    public function __construct($curlHandle, $path, $accept, array $params = NULL) {
        parent::__construct($curlHandle, $path, $accept, NULL);
        $this->httpMethod = "POST";
        $this->contentType = parent::URLENCODED;
        $this->path = $path;
        $this->curlHandle = $curlHandle;
        $this->accept = $accept;

        // set the http method
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $this->httpMethod);

        if ($params != NULL && count($params) > 0) {
            $this->body = http_build_query($params);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->body);
        }
    }

}
