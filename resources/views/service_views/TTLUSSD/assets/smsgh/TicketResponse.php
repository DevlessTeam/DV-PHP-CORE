<?php

/**
 * This class represents the Unity Http API Support Ticket Responses
 * @author 	Arsene Tochemey GANDOTE
 * @since 	09/12/2013
 * @version 1.0
 * @copyright (c) 2013 SMSGH Limited
 * @category API
 */
class TicketResponse {

    private $object;

    /**
     *
     * @param
     *        	Json Object $json
     */
    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass ();
    }

    /**
     * Get the Id of the ApiTicketResponse
     */
    public function getId() {
        return $this->object->Id;
    }

    /**
     * Get the Content
     */
    public function getContent() {
        return $this->object->Content;
    }

    /**
     * Get the attachment
     */
    public function getAttachment() {
        return $this->object->Attachment;
    }

    /**
     * Get the time
     */
    public function getTime() {
        return $this->object->Time;
    }

    public function setContent($value) {
        if ($value === null || is_string($value)) {
            $this->object->Content = $value;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'string'"));
    }

}
