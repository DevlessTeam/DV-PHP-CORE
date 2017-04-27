<?php

/**
 * Helps to add the Basic Authorization header to the http request for authorization
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class BasicAuth{
    
    private $userName;
    private $password;
    
    /**
     * Intialise the properties
     */
    public function __construct($userName, $password) {
        $this->userName = $userName;
        $this->password = $password;
    }
    
    public function setUserName($userName) {
        $this->userName = $userName;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    public function getUserName() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }


}
