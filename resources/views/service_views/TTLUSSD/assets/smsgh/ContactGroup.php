<?php

class ContactGroup {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json = null) {
        if ($json === null)
            $this->object = new stdClass;
        else if (is_object($json))
            $this->object = $json;
        else
            throw new Exception('Bad parameter');
    }

    /**
     * Gets accountId.
     */
    public function getAccountId() {
        return @$this->object->AccountId;
    }

    /**
     * Gets contactCount.
     */
    public function getContactCount() {
        return @$this->object->ContactCount;
    }

    /**
     * Gets groupId.
     */
    public function getGroupId() {
        return @$this->object->GroupId;
    }

    /**
     * Gets name.
     */
    public function getName() {
        return @$this->object->Name;
    }

    /**
     * Sets name.
     */
    public function setName($value) {
        if ($value === null || is_string($value)) {
            $this->object->Name = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
