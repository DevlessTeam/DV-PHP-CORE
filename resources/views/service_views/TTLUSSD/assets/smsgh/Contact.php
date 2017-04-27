<?php

class Contact {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json = null) {
        if ($json === null) {
            $this->object = new stdClass;
        } else if (is_object($json)) {
            $this->object = $json;
        } else {
            throw new Exception('Bad parameter');
        }
    }

    /**
     * Gets contactId.
     */
    public function getContactId() {
        return @$this->object->ContactId + 0;
    }

    /**
     * Gets custom1.
     */
    public function getCustom1() {
        return @$this->object->Custom1;
    }

    /**
     * Gets custom2.
     */
    public function getCustom2() {
        return @$this->object->Custom2;
    }

    /**
     * Gets custom3.
     */
    public function getCustom3() {
        return @$this->object->Custom3;
    }

    /**
     * Gets firstName.
     */
    public function getFirstName() {
        return @$this->object->FirstName;
    }

    /**
     * Gets groupId.
     */
    public function getGroupId() {
        return @$this->object->GroupId;
    }

    /**
     * Gets groupName.
     */
    public function getGroupName() {
        return @$this->object->GroupName;
    }

    /**
     * Gets mobileNumber.
     */
    public function getMobileNumber() {
        return @$this->object->MobileNumber;
    }

    /**
     * Gets owner.
     */
    public function getOwner() {
        return @$this->object->Owner;
    }

    /**
     * Gets surname.
     */
    public function getSurname() {
        return @$this->object->Surname;
    }

    /**
     * Gets title.
     */
    public function getTitle() {
        return @$this->object->Title;
    }

    /**
     * Sets custom1.
     */
    public function setCustom1($value) {
        if ($value === null || is_string($value)) {
            $this->object->Custom1 = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets custom2.
     */
    public function setCustom2($value) {
        if ($value === null || is_string($value)) {
            $this->object->Custom2 = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets custom3.
     */
    public function setCustom3($value) {
        if ($value === null || is_string($value)) {
            $this->object->Custom3 = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets firstName.
     */
    public function setFirstName($value) {
        if ($value === null || is_string($value)) {
            $this->object->FirstName = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets groupId.
     */
    public function setGroupId($value) {
        if (is_numeric($value)) {
            $this->object->GroupId = $value + 0;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'number'");
    }

    /**
     * Sets mobileNumber.
     */
    public function setMobileNumber($value) {
        if ($value === null || is_string($value)) {
            $this->object->MobileNumber = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets surname.
     */
    public function setSurname($value) {
        if ($value === null || is_string($value)) {
            $this->object->Surname = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets title.
     */
    public function setTitle($value) {
        if ($value === null || is_string($value)) {
            $this->object->Title = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
