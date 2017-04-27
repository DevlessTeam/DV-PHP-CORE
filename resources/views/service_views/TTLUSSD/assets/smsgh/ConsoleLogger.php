<?php

/**
 * Default {@link RequestLogger} used by {@link BasicHttpClient}.
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class ConsoleLogger implements RequestLogger {

    private $enableLog;

    public function __construct($enableLog = TRUE) {
        $this->enableLog = $enableLog;
    }

    public function isLoggingEnabled() {
        return $this->enableLog;
    }

    public function log($mesg) {
        echo $mesg . "\n";
    }

    public function logRequest($httpRequest, $headers) {
        $this->log("=== HTTP Request ===");
        $this->logHeaders($headers);
        if ($httpRequest != null && $httpRequest instanceof HttpRequest) {
            $this->log("Request Method :" . $httpRequest->getHttpMethod());
            $this->log("Content : " . $httpRequest->getBody());
            $this->log("Accept : " . $httpRequest->getAccept());
        }
    }

    public function logResponse($response) {
        if ($response != null && $response instanceof HttpResponse) {
            $this->log("=== HTTP Response ===");
            $this->log("Receive url :" . $response->getUrl());
            $this->log("Status : " . $response->getStatus());
            echo $response->getHeaders();
            echo $response->getBody();
        }
    }

    private function logHeaders($headers) {
        if (isset($headers) && is_array($headers)) {
            foreach ($headers as $key => $value) {
                if (is_array($value)) {
                    // Here we ignore all headers with multiple values
                    continue;
                }
                $this->log($key . " : " . $value);
            }
        }
    }

    public function logMultipartRequest($headers, $accept, $method) {
        $this->log("=== HTTP Request ===");
        $this->logHeaders($headers);
        $this->log("Request Method :" . $method);
        $this->log("Accept : " . $accept);
    }

}
