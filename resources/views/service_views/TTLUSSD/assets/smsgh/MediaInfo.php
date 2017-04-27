<?php

/**
 * Description of MediaInfo
 *
 * @author Arsene Tochemey GANDOTE
 */
class MediaInfo {

    public $contentName;
    public $libraryId;
    public $destinationFolder;
    public $preference;
    public $width;
    public $height;
    public $drmProtect;
    public $tags;
    public $streamable;
    public $contentText;
    public $displayText;

    public function __construct() {
        $this->contentName = "";
        $this->libraryId = "";
        $this->destinationFolder = "";
        $this->preference = "";
        $this->width = 0;
        $this->height = 0;
        $this->drmProtect = "false";
        $this->tags = array();
        $this->streamable = "false";
        $this->contentText = "";
        $this->displayText = "";
    }

}
