<?php

/**
 * Description of TopupLocation
 *
 * @author smsgh
 */
class TopupLocation {

    private $object;

    public function __construct($json = null) {
        if ($json === null) {
            $this->object = new stdClass;
        } else if (is_object($json)) {
            $this->object = $json;
        } else {
            throw new Exception('Bad parameter');
        }
    }

    public function getId() {
        return @$this->object->Id;
    }

    public function getCity() {
        return @$this->object->City;
    }

    public function setCity($city) {
        if (is_null($city) || is_string($city)) {
            $this->object->City = $city;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    public function getArea() {
        return @$this->object->Area;
    }

    public function setArea($area) {
        if (is_null($area) || is_string($area)) {
            $this->object->Area = $area;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    public function getRegion() {
        return @$this->object->Region;
    }

    public function setRegion($region) {
        if (is_null($area) || is_string($region)) {
            $this->object->Region = $region;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    public function getDetails() {
        return @$this->object->Details;
    }

    public function setDetails($details) {
        if (is_null($details) || is_string($details)) {
            $this->object->Details = $details;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    public function getDescription() {
        return @$this->object->Description;
    }

    public function setDescription($description) {
        if (is_null($description) || is_string($description)) {
            $this->object->Description = $description;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'string'");
    }

    public function setLatitude($latitude) {
        if (is_null($latitude) || is_double($latitude)) {
            $this->object->Latitude = $latitude;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'double'");
    }

    public function getLatitude() {
        return @$this->object->Latitude;
    }

    public function setLongitude($longitude) {
        if (is_null($longitude) || is_double($longitude)) {
            $this->object->Longitude = $longitude;
            return $this;
        }
        throw new Exception
        ("Parameter value must be of type 'double'");
    }

    public function getLongitude() {
        return @$this->object->Longitude;
    }

}
