<?php

/**
 * SmsghApi
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class ApiHost {

    private $auth;
    private $hostname;
    private $port;
    private $contextPath;
    private $timeout;

    public function __construct($auth = null, $hostname = "api.smsgh.com", $port = -1, $contextPath = "v3", $timeout = 5000) {
        $this->auth = $auth;
        $this->hostname = $hostname;
        $this->port = $port;
        $this->contextPath = $contextPath;
        $this->timeout = $timeout;
    }

    public function getAuth() {
        return $this->auth;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function getPort() {
        return $this->port;
    }

    public function getContextPath() {
        return $this->contextPath;
    }

    public function getTimeout() {
        return $this->timeout;
    }

    public function setAuth($auth) {
        $this->auth = $auth;
        return $this;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
        return $this;
    }

    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    public function setContextPath($contextPath) {
        $this->contextPath = $contextPath;
        return $this;
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
        return $this;
    }

}
