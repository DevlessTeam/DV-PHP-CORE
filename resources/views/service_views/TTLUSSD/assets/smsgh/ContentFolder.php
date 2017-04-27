<?php

/**
 * Description of ContentFolder
 *
 * @author Arsene Tochemey GANDOTE
 */
class ContentFolder {

    private $object;

    public function __construct($json = null) {
        $this->object = is_object($json) ? $json : new stdClass;

        $arr = array();
        if (isset($this->object->SubFolders)) {
            foreach ($this->object->SubFolders as $o) {
                $arr[] = new ContentFolder($o);
            }
        }
        $this->object->SubFolders = $arr;


        $arr = array();
        if (isset($this->object->ContentMedias)) {
            foreach ($this->object->ContentMedias as $o) {
                $arr[] = new ContentMedia($o);
            }
        }
        $this->object->ContentMedias = $arr;
    }

    public function getContentFolderId() {
        return $this->object->ContentFolderId;
    }

    public function getContentLibraryId() {
        return $this->object->ContentLibraryId;
    }

    public function getFolderName() {
        return $this->object->FolderName;
    }

    public function getAbsolutePath() {
        return $this->object->AbsolutePath;
    }

    public function getDateCreated() {
        return $this->object->DateCreated;
    }

    public function getDateModified() {
        return $this->object->DateModified;
    }

    public function getParentId() {
        return $this->object->ParentId;
    }

    public function getSubFolderCount() {
        return $this->object->SubFolderCount;
    }

    public function getContentMediaCount() {
        return $this->object->ContentMediaCount;
    }

    public function getFolders() {
        return $this->object->SubFolders;
    }

    public function getMedias() {
        return $this->object->ContentMedias;
    }

}
