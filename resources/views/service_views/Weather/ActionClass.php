<?php
/**
 * Created by Devless.
 * Author: Add username here
 * Date Created: 22nd of June 2017 09:17:41 AM
 * Service: Weather
 * Version: 1.3.0
 */

use App\Helpers\ActionClass;
//Action method for serviceName
class Weather
{
    public $serviceName = 'Weather';
     

    /**
     * get weather object
     * @param string $cityname
     * @param string $country
     * @return integer
     * @ACL private
     */
     
    private function getWeatherObject($cityName,$country){
        $data = json_decode(file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$cityName.','.$country.'&APPID=8b18d5a797fe0ccdce653fb2cc1e56af'));
        return $data;
    } 

    
    /**
     * get the humidity in any city in a particular country 
     * @param string $cityname
     * @param string country
     * @return integer
     * @ACL private
     */
    
    public function getHumidityFor($cityName,$country){
        $json_data = self::getWeatherObject($cityName,$country);
        return $json_data->main->humidity;
    }
    
    /**
     * Get temperature of cities in a country in Celcius
     * @param string $cityname
     * @param string $country
     * @return integer
     * @ACL public
     */
    public function getCelciusTemperatureFor($cityName, $country){
        return $this->getTemperature($cityName, $country, 'celcius');
    }

    /**
     * Get temperature of cities in a country in kelvin
     * @param string $cityname
     * @param string $country
     * @return integer
     * @ACL public
     */
    public function getKelvinTemperatureFor($cityName, $country){
        return $this->getTemperature($cityName, $country, 'kelvin');
    }

    /**
     * Get temperature of cities in a country in fahrenheit
     * @param string $cityname
     * @param string $country
     * @return integer
     * @ACL public
     */
    public function getfahrenheitTemperatureFor($cityName, $country){
        return $this->getTemperature($cityName, $country, 'fahrenheit');
    }

    /**
     * get temprature of any city in a country in either (kelvin, fahrenheit, celcius)
     * @param string $cityname
     * @param string $country
     * @param string $unit
     * @return integer
     * @ACL public
     */
    public function getTemperature($cityName,$country,$unit){
        $json_data = self::getWeatherObject($cityName,$country);
        $base_unit = $json_data->main->temp_max;
       
        $getTemp = [
            'kelvin'     =>  $base_unit,
            'fahrenheit' => ($base_unit)* 9/5 - 459.67,
            'celcius'    =>  ($base_unit) - 273.15,
        ];
        return $getTemp[$unit];
        
    }
    
    
    /**
     * get the wind speed in any city in a particular country 
     * @param string $cityname
     * @param string $country
     * @return integer
     * @ACL private
     */
    
    public function getWindSpeedFor($cityName,$country){
        $json_data = self::getWeatherObject($cityName,$country);
        return $json_data->wind->speed * 2.2367;
    }
    
    /**
     * get the wind direction in any city in a particular country 
     * @param string $cityname
     * @param string $country
     * @return integer 
     * @ACL private
     */
    
    public function getWindDirectionFor($cityName,$country){
        $json_data = self::getWeatherObject($cityName,$country);
        return $json_data->wind->deg;

    }

    /**
     * List out all possible callbale methods as well as get docs on specific method 
     * @param string $methodToGetDocsFor
     * @return $this;
     */
    public function help($methodToGetDocsFor=null)
    {
        $serviceInstance = $this;
        $actionClass = new ActionClass();
        return $actionClass->help($serviceInstance, $methodToGetDocsFor=null);   
    }
    /**
     * This method will execute on service importation
     * @ACL private
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

