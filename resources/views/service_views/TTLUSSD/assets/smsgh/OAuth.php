<?php


/**
 * Description of OAuth
 *
 * @author Arsene Tochemey GANDOTE
 *
 */
class OAuth {

    private $bearerToken;
    
    public function __construct($token) {
        $this->bearerToken = $token;
    }
    
    public function getBearerToken() {
        return $this->bearerToken;
    }

    
}
