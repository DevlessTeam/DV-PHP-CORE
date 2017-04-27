<?php

/**
 * Description of Tag
 *
 * @author Arsene Tochemey GANDOTE
 */
class Tag {

    private $object;

    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    public function getKey() {
        return $this->object->Key;
    }

    public function getValue() {
        return $this->object->Value;
    }

}
