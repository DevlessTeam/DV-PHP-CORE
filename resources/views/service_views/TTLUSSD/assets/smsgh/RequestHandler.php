<?php

/**
 * Interface that defines the request lifecycle used by {@link AbstractHttpClient}.
 * RequestHandler is composed of many sub-interfaces
 * so that each handler can be set independently if needed. You can provide your
 * own implementation by providing it in the client constructor. 
 * 
 * @author Arsene Tochemey GANDOTE
 */
interface RequestHandler {
    /**
     * Open the Http connection
     * @param cURL Handle curlHandle An curl handle
     * @param string $url The connection Url
     * @return cURL handle on success or FALSE on errors
     */
    public function openConnection($curlHandle, $url);
    
    /**
     * Prepares a previously opened connection. It is called before writing to outputstream.
     * So you can set or modify the connection properties
     * @param cURL $curlHandle An curl handle
     * @param string $contentType MIME Type
     * @param string $accept Http Response excepted format
     */
    public function prepareConnection($curlHandle, $contentType, $accept);
    
    /**
     *  Writes to an open, prepared connection.
     * @param cURL $curlHandle An open, prepared curl handle
     */
    public function writeToStream($curlHandle);
}
