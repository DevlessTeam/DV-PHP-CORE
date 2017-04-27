<?php

class AccountContact {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    /**
     * Gets accountContactId.
     */
    public function getAccountContactId() {
        return @$this->object->AccountContactId + 0;
    }

    /**
     * Gets address1.
     */
    public function getAddress1() {
        return @$this->object->Address1;
    }

    /**
     * Gets address2.
     */
    public function getAddress2() {
        return @$this->object->Address2;
    }

    /**
     * Gets city.
     */
    public function getCity() {
        return @$this->object->City;
    }

    /**
     * Gets country.
     */
    public function getCountry() {
        return @$this->object->Country;
    }

    /**
     * Gets firstName.
     */
    public function getFirstName() {
        return @$this->object->FirstName;
    }

    /**
     * Gets lastName.
     */
    public function getLastName() {
        return @$this->object->LastName;
    }

    /**
     * Gets province.
     */
    public function getProvince() {
        return @$this->object->Province;
    }

    /**
     * Gets postalCode.
     */
    public function getPostalCode() {
        return @$this->object->PostalCode;
    }

    /**
     * Gets primaryEmail.
     */
    public function getPrimaryEmail() {
        return @$this->object->PrimaryEmail;
    }

    /**
     * Gets primaryPhone.
     */
    public function getPrimaryPhone() {
        return @$this->object->PrimaryPhone;
    }

    /**
     * Gets privateNote.
     */
    public function getPrivateNote() {
        return @$this->object->PrivateNote;
    }

    /**
     * Gets publicNote.
     */
    public function getPublicNote() {
        return @$this->object->PublicNote;
    }

    /**
     * Gets secondaryEmail.
     */
    public function getSecondaryEmail() {
        return @$this->object->SecondaryEmail;
    }

    /**
     * Gets secondaryPhone.
     */
    public function getSecondaryPhone() {
        return @$this->object->SecondaryPhone;
    }

    /**
     * Sets address1.
     */
    public function setAddress1($value) {
        if ($value === null || is_string($value)) {
            $this->object->Address1 = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets address2.
     */
    public function setAddress2($value) {
        if ($value === null || is_string($value)) {
            $this->object->Address2 = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets city.
     */
    public function setCity($value) {
        if ($value === null || is_string($value)) {
            $this->object->City = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets country.
     */
    public function setCountry($value) {
        if ($value === null || is_string($value)) {
            $this->object->Country = $value;
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
     * Sets lastName.
     */
    public function setLastName($value) {
        if ($value === null || is_string($value)) {
            $this->object->LastName = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets province.
     */
    public function setProvince($value) {
        if ($value === null || is_string($value)) {
            $this->object->Province = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets postalCode.
     */
    public function setPostalCode($value) {
        if ($value === null || is_string($value)) {
            $this->object->PostalCode = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets primaryEmail.
     */
    public function setPrimaryEmail($value) {
        if ($value === null || is_string($value)) {
            $this->object->PrimaryEmail = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets primaryPhone.
     */
    public function setPrimaryPhone($value) {
        if ($value === null || is_string($value)) {
            $this->object->PrimaryPhone = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets privateNote.
     */
    public function setPrivateNote($value) {
        if ($value === null || is_string($value)) {
            $this->object->PrivateNote = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets publicNote.
     */
    public function setPublicNote($value) {
        if ($value === null || is_string($value)) {
            $this->object->PublicNote = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets secondaryEmail.
     */
    public function setSecondaryEmail($value) {
        if ($value === null || is_string($value)) {
            $this->object->SecondaryEmail = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets secondaryPhone.
     */
    public function setSecondaryPhone($value) {
        if ($value === null || is_string($value)) {
            $this->object->SecondaryPhone = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
