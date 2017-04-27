<?php

/**
 * Description of ContentMedia
 *
 * @author Arsene Tochemey GANDOTE
 */
class ContentMedia {

    private $object;

    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;

        $arr = array();
        if (isset($this->object->Tags)) {
            foreach ($this->object->Tags as $o) {
                $arr[] = new Tag($o);
            }
        }
        $this->object->Tags = $arr;
    }

    public function getId() {
        return $this->object->Id;
    }

    public function getAccountId() {
        return $this->object->AccountId;
    }

    public function getName() {
        return $this->object->Name;
    }

    public function getLibraryId() {
        return $this->object->LibraryId;
    }

    public function getLocationPath() {
        return $this->object->LocationPath;
    }

    public function getTags() {
        return $this->object->Tags;
    }

    public function getType() {
        return $this->object->Type;
    }

    public function getPreference() {
        return $this->object->Preference;
    }

    public function isDrmProtect() {
        return $this->object->DrmProtect;
    }

    public function getEncodingStatus() {
        return $this->object->EncodingStatus;
    }

    public function isStreamable() {
        return $this->object->Streamable;
    }

    public function getDisplaytext() {
        return $this->object->DisplayText;
    }

    public function getContentText() {
        return $this->object->ContentText;
    }

    public function isApproved() {
        return $this->object->Approved;
    }

    public function isDeleted() {
        return $this->object->Deleted;
    }

    public function getDateCreated() {
        return $this->object->DateCreated;
    }

    public function getDateModified() {
        return $this->object->DateModified;
    }

    public function getDateDeleted() {
        return $this->object->DateDeleted;
    }

    public function getCallbackUrl() {
        return $this->object->CallbackUrl;
    }

}
