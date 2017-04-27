<?php

class MoKeyWord {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    /**
     * Gets alias1.
     */
    public function getAlias1() {
        return @$this->object->Alias1;
    }

    /**
     * Gets alias2.
     */
    public function getAlias2() {
        return @$this->object->Alias2;
    }

    /**
     * Gets alias3.
     */
    public function getAlias3() {
        return @$this->object->Alias3;
    }

    /**
     * Gets alias4.
     */
    public function getAlias4() {
        return @$this->object->Alias4;
    }

    /**
     * Gets alias5.
     */
    public function getAlias5() {
        return @$this->object->Alias5;
    }

    /**
     * Gets id.
     */
    public function getId() {
        return @$this->object->Id;
    }

    /**
     * Gets isActive.
     */
    public function isActive() {
        return @$this->object->IsActive;
    }

    /**
     * Gets isDefault.
     */
    public function isDefault() {
        return @$this->object->IsDefault;
    }

    /**
     * Gets keyword.
     */
    public function getKeyword() {
        return @$this->object->Keyword;
    }

    /**
     * Gets numberPlanId.
     */
    public function getNumberPlanId() {
        return @$this->object->NumberPlanId;
    }

    /**
     * Sets alias1.
     */
    public function setAlias1($value) {
        if ($value === null || is_string($value)) {
            $this->object->Alias1 = $value;
            return $this;
        }
        throw new Exception("Parameter should be of type 'string'");
    }

    /**
     * Sets alias2.
     */
    public function setAlias2($value) {
        if ($value === null || is_string($value)) {
            $this->object->Alias2 = $value;
            return $this;
        }
        throw new Exception("Parameter should be of type 'string'");
    }

    /**
     * Sets alias3.
     */
    public function setAlias3($value) {
        if ($value === null || is_string($value)) {
            $this->object->Alias3 = $value;
            return $this;
        }
        throw new Exception("Parameter should be of type 'string'");
    }

    /**
     * Sets alias4.
     */
    public function setAlias4($value) {
        if ($value === null || is_string($value)) {
            $this->object->Alias4 = $value;
            return $this;
        }
        throw new Exception("Parameter should be of type 'string'");
    }

    /**
     * Sets alias5.
     */
    public function setAlias5($value) {
        if ($value === null || is_string($value)) {
            $this->object->Alias5 = $value;
            return $this;
        }
        throw new Exception("Parameter should be of type 'string'");
    }

    /**
     * Sets keyword.
     */
    public function setKeyword($value) {
        if ($value === null || is_string($value)) {
            $this->object->Keyword = $value;
            return $this;
        }
        throw new Exception("Parameter should be of type 'string'");
    }

    /**
     * Sets NumberPlanId.
     */
    public function setNumberPlanId($value) {
        if (is_numeric($value)) {
            $this->object->NumberPlanId = $value + 0;
            return $this;
        }
        throw new Exception("Parameter should be of type 'number'");
    }

}
