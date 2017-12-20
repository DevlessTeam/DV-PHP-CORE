<?php

/**
 * Created by Devless.
 * Author: eddymens
 * Date Created: 27th of January 2017 02:50:55 AM
 * @Service: uploader
 * @Version: 1.0
 **/

use App\Helpers\DataStore;
require 'vendor/Cloudinary.php';
require 'vendor/Uploader.php';
require 'vendor/Api.php';


// Action method for serviceName
class uploader
{
    public $serviceName = 'uploader';
    public $tableName   = 'cloud_details';

    public $config = []; 


    /**
     * Default constructor to take data from the data from the service table (cloud_details)
     * Data is taken from the first input
     * @ACL public
     */
    public function __construct()
    {
        $cred = DataStore::service($this->serviceName,$this->tableName)->queryData();
        $this->config = (isset($cred['payload']['results'][0]))? $cred['payload']['results'][0]:['api_key'=>'','api_secret'=>'','cloud_name'=>''];
    }

    /**
     * Upload image to your cloudinary account
     * Has optional params for transforming of images
     * login to https://cloudinary.com/console to get cloud name,api key,api secret 
     * @param String  cloud name
     * @param String/Number api-key 
     * @param String/Number api-secret
     * @param String image file location 
     * @param optional String/Number height default is original height of image 
     * @param optional String/Number width default original width of image
     * @ACL public
     */
    private function setupDetails($cloud_name,$api_key,$api_secret){
        \Cloudinary::config([
            "cloud_name" => $this->config->cloud_name,
            "api_key"    => $this->config->api_key,
            "api_secret" => $this->config->api_secret
        ]);
    }

    /**
     * Upload image to your cloudinary account
     * Has optional params for transforming of images
     * login to https://cloudinary.com/console to get cloud name,api key,api secret 
     * @param String  cloud name
     * @param String/Number api-key 
     * @param String/Number api-secret
     * @param String image file location 
     * @param optional String/Number height default is original height of image 
     * @param optional String/Number width default original width of image
     * @ACL public
     */
    public function upload($image,$height = '',$width = '',$cloud_name='',$api_key='',$api_secret='')
    {
        $this->setupDetails($cloud_name,$api_key,$api_secret);
        $image_meta = \Cloudinary\Uploader::upload($image,[
                "width"     => $width,
                "height"    => $height
            ]);
        return $image_meta['url'];
    }

    /**
     * This method will execute on service importation
     * 5@ACL private
     */
    public function __onImport()
{
        //add code here

    }


    /**
     * This method will execute on service exportation
     * @ACL private
     */
    public function __onDelete()
    {
        //add code here

    }
}