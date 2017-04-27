<?php

class Sender {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    /**
     * Gets accountId.
     */
    public function getAccountId() {
        return @$this->object->AccountId;
    }

    /**
     * Gets address.
     */
    public function getAddress() {
        return @$this->object->Address;
    }

    /**
     * Gets id.
     */
    public function getId() {
        return @$this->object->Id;
    }

    /**
     * Gets isDeleted.
     */
    public function isDeleted() {
        return @$this->object->IsDeleted;
    }

    /**
     * Gets timeAdded.
     */
    public function getTimeAdded() {
        return @$this->object->TimeAdded;
    }

    /**
     * Gets timeDeleted.
     */
    public function getTimeDeleted() {
        return @$this->object->TimeDeleted;
    }

    /**
     * Sets address.
     */
    public function setAddress($value) {
        if ($value === null || is_string($value)) {
            $this->object->Address = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
