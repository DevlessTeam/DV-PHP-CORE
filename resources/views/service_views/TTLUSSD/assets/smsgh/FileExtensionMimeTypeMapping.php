<?php

/**
 * Description of FileExtensionMimeTypeMapping
 *
 * @author Arsene Tochemey GANDOTE
 */
class FileExtensionMimeTypeMapping {

    private $mappings;

    private function __construct() {

        $mimefilePath = __DIR__ . "/mime.types";
        $regex = "/([\w\+\-\.\/]+)\t+([\w\s]+)/i";
        $lines = file($mimefilePath, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            if (substr($line, 0, 1) == '#') {
                continue;
            } // skip comments 
            if (!preg_match($regex, $line, $matches)) {
                continue;
            } // skip mime types w/o any extensions 
            $mime = $matches[1];
            $extensions = explode(" ", $matches[2]);
            foreach ($extensions as $ext) {
                $mimeArray[trim($ext)] = $mime;
            }
        }

        $this->mappings = $mimeArray;
    }

    public static function getMimeType($filename) {
        $mimefilePath = __DIR__ . "/mime.types";
        $fileext = substr(strrchr($filename, '.'), 1);
        if (empty($fileext)) {
            return (false);
        }
        $regex = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($fileext\s)/i";
        $lines = file($mimefilePath);
        foreach ($lines as $line) {
            if (substr($line, 0, 1) == '#') {
                continue;
            } // skip comments 
            $line = rtrim($line) . " ";
            if (!preg_match($regex, $line, $matches)) {
                continue;
            } // no match to the extension 
            return ($matches[1]);
        }
        return (false); // no match at all 
    }

}
