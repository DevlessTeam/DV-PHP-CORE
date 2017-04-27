<?php

/**
 *  AbstractApi This class will be implemented by all the APIs classes of the SDKs
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
abstract class AbstractApi {

    protected $apiHost;
    protected $httpClient;

    public function __construct($apiHost, $enableConsoleLog = TRUE) {

        if ($apiHost instanceof ApiHost) {
            $this->apiHost = $apiHost;

            // Here we can set all the necessary routines
            $baseUrl = "http://" . $this->apiHost->getHostname();

            // set the port when set
            if ($this->apiHost->getPort() > 0) {
                $baseUrl .= ":" . $this->apiHost->getPort();
            }

            // Set the context path in case it is defined
            if (!is_null($this->apiHost->getContextPath()) && $this->apiHost->getContextPath() != "") {
                $baseUrl .= "/" . $this->apiHost->getContextPath();
            }

            // set the httpclient object to fire requests
            $this->httpClient = BasicHttpClient::init($baseUrl, $enableConsoleLog);

            // Set the authorization headers
            if ($this->apiHost->getAuth() instanceof BasicAuth) {
                $basicAuth = $this->apiHost->getAuth();
                $this->httpClient->setBasicAuth($basicAuth->getUserName(), $basicAuth->getPassword());
            }

            if ($this->apiHost->getAuth() instanceof OAuth) {
                $this->httpClient->setOAuth2BearerToken($this->apiHost->getAuth()->getBearerToken());
            }

            // set the timeouts
            $this->httpClient->setConnectionTimeout($this->apiHost->getTimeout());
            $this->httpClient->setReadTimeout($this->apiHost->getTimeout());
        } else {
            trigger_error("Wrong Argument", E_ERROR);
        }
    }

}
