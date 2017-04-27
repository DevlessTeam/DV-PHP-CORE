<?php

class Message {

    private $object;

    /**
     * Primary constructor.
     */
    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    /**
     * Gets apiMessageType.
     */
    public function getType() {
        return @$this->object->Type;
    }

    /**
     * Gets clientReference.
     */
    public function getClientReference() {
        return @$this->object->ClientReference;
    }

    /**
     * Gets content.
     */
    public function getContent() {
        return @$this->object->Content;
    }

    /**
     * Gets direction.
     */
    public function getDirection() {
        return @$this->object->Direction;
    }

    /**
     * Gets flashMessage.
     */
    public function getFlashMessage() {
        return @$this->object->FlashMessage;
    }

    /**
     * Gets from.
     */
    public function getFrom() {
        return @$this->object->From;
    }

    /**
     * Gets messageId.
     */
    public function getMessageId() {
        return @$this->object->MessageId;
    }

    /**
     * Gets networkId.
     */
    public function getNetworkId() {
        return @$this->object->NetworkId;
    }

    /**
     * Gets rate.
     */
    public function getRate() {
        return @$this->object->Rate;
    }

    /**
     * Gets registeredDelivery.
     */
    public function getRegisteredDelivery() {
        return @$this->object->RegisteredDelivery;
    }

    /**
     * Gets status.
     */
    public function getStatus() {
        return @$this->object->Status;
    }

    /**
     * Gets time.
     */
    public function getTime() {
        return @$this->object->Time;
    }

    /**
     * Gets to.
     */
    public function getTo() {
        return @$this->object->To;
    }

    /**
     * Gets udh.
     */
    public function getUdh() {
        return @$this->object->Udh;
    }

    /**
     * Gets units.
     */
    public function getUnits() {
        return @$this->object->Units;
    }

    /**
     * Gets updateTime.
     */
    public function getUpdateTime() {
        return @$this->object->UpdateTime;
    }

    public function getBillingInfo() {
        return @$this->object->BillingInfo;
    }

    /**
     * Sets Type.
     */
    public function setType($value) {
        if (is_int($value)) {
            if ($value != MessageType::BINARY_MESSAGE && $value != MessageType::TEXT_MESSAGE && $value != MessageType::UNICODE_MESSAGE) {
                $this->object->Type = "Unset";
            } else {
                $this->object->Type = $value;
            }
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'int'");
    }

    /**
     * Sets clientReference.
     */
    public function setClientReference($value) {
        if ($value === null || is_string($value)) {
            $this->object->ClientReference = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets content.
     */
    public function setContent($value) {
        if ($value === null || is_string($value)) {
            $this->object->Content = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets flashMessage.
     */
    public function setFlashMessage($value) {
        if (is_bool($value)) {
            $this->object->FlashMessage = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets from.
     */
    public function setFrom($value) {
        if ($value === null || is_string($value)) {
            $this->object->From = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets registeredDelivery.
     */
    public function setRegisteredDelivery($value) {
        if (is_bool($value)) {
            $this->object->RegisteredDelivery = $value ? "true" : "false";
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'bool'");
    }

    /**
     * Sets time.
     */
    public function setTime($value) {
        if ($value === null || is_string($value)) {
            $this->object->Time = $value;
            return $this;
        } elseif (is_int($value) && $value > 0) {
            $this->object->Time = gmdate('Y-m-d H:i:s', $time);
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets to.
     */
    public function setTo($value) {
        if ($value === null || is_string($value)) {
            $this->object->To = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets udh.
     */
    public function setUdh($value) {
        if ($value === null || is_string($value)) {
            $this->object->Udh = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    /**
     * Sets BillingInfo.
     */
    public function setBillingInfo($value) {
        if ($value === null || is_string($value)) {
            $this->object->BillingInfo = $value;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
