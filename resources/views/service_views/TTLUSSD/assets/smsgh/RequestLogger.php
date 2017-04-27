<?php

/**
 * HTTP request logger used by {@link BasicHttpClient}.
 * @author Arsene Tochemey GANDOTE
 */
interface RequestLogger {

    /**
     * Determine whether requests should be logged.
     */
    public function isLoggingEnabled();

    /**
     *  Writes a log message.
     * @param string $mesg The log message
     */
    public function log($mesg);

    /**
     * Log the HTTP request and content to be sent with the request.
     * @param HttpRequest $httpRequest The request to log
     * @param array $headers Request headers
     */
    public function logRequest($httpRequest, $headers);

    /**
     * Logs the HTTP response.
     * @param HttpResponse $response The Http Response
     */
    public function logResponse($response);

    /**
     * Log the HTTP request while uploading file onto the server.
     * @param array $headers
     * @param string $accept Response Mime Type
     * @param string $method Request method
     */
    public function logMultipartRequest($headers, $accept, $method);
}
