<?php

/**
 * Description of ContentLibrary
 *
 * @author Arsene Tochemey GANDOTE
 */
class ContentLibrary {

    private $object;

    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;
    }

    public function getAccountId() {
        return $this->object->AccountId;
    }

    public function getLibraryId() {
        return $this->object->LibraryId;
    }

    public function getName() {
        return $this->object->Name;
    }

    public function getShortName() {
        return $this->object->ShortName;
    }

    public function getDateCreated() {
        return $this->object->DateCreated;
    }

    public function getDateModified() {
        return $this->object->DateModified;
    }

    public function getFolderId() {
        return $this->object->FolderId;
    }

    public function setName($name) {
        if ($name === null || is_string($name)) {
            $this->object->Name = $name;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    public function setShortName($shortName) {
        if ($shortName === null || is_string($shortName)) {
            $this->object->ShortName = $shortName;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

}
