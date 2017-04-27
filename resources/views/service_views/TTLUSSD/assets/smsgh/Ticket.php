<?php

/**
 * This class represents the Unity Http API Support Tickets
 * @author 	Arsene Tochemey GANDOTE
 * @since 	09/12/2013
 * @version 1.0
 * @copyright (c) 2013 SMSGH Limited
 * @category API
 */
class Ticket {

    private $object;

    public function __construct($json = null) {
        if ($json === null)
            $this->object = new stdClass ();
        else if (is_object($json)) {
            $this->object = $json;

            $arr = array();
            if (isset($json->Responses))
                foreach ($json->Responses as $o)
                    $arr [] = new TicketResponse($o);
            $this->object->Responses = $arr;
        } else
            throw new Exception('Bad parameter');
    }

    public function getAccountId() {
        return $this->object->AccountId;
    }

    public function getAttachment() {
        return $this->object->Attachment;
    }

    public function getId() {
        return $this->object->Id;
    }

    public function getLastUpdated() {
        return $this->object->LastUpdated;
    }

    public function getRating() {
        return $this->object->Rating;
    }

    public function getContent() {
        return $this->object->Content;
    }

    public function getRecipients() {
        return $this->object->Recipients;
    }

    public function getTimeAdded() {
        return $this->object->TimeAdded;
    }

    public function getTimeAssigned() {
        return $this->object->TimeAssigned;
    }

    public function getTimeClosed() {
        return $this->object->TimeClosed;
    }

    public function getAssignedTo() {
        return $this->object->AssignedTo;
    }

    public function getSupportDepartmentId() {
        return $this->object->SupportDepartmentId;
    }

    public function getSupportCategoryId() {
        return $this->object->SupportCategoryId;
    }

    public function getSupportStatusId() {
        return $this->object->SupportStatusId;
    }

    public function getPriority() {
        return $this->object->Priority;
    }

    public function getSource() {
        return $this->object->Source;
    }

    public function getSubject() {
        return $this->object->Subject;
    }

    public function getResponses() {
        return $this->object->Responses;
    }

    public function setSupportDepartmentId($value) {
        if (is_numeric($value)) {
            $this->object->SupportDepartmentId = $value + 0;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'numeric'"));
    }

    public function setSupportCategoryId($value) {
        if (is_numeric($value)) {
            $this->object->SupportCategoryId = $value + 0;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'numeric'"));
    }

    public function setPriority($value) {
        if (is_numeric($value)) {
            $this->object->Priority = $value + 0;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'numeric'"));
    }

    public function setSource($value) {
        if ($value === null || is_string($value)) {
            $this->object->Source = $value;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'string'"));
    }

    public function setSubject($value) {
        if ($value === null || is_string($value)) {
            $this->object->Subject = $value;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'string'"));
    }

    public function setContent($value) {
        if ($value === null || is_string($value)) {
            $this->object->Content = $value;
            return $this;
        }
        throwException(new Exception("Parameter value must be of type 'string'"));
    }

}
