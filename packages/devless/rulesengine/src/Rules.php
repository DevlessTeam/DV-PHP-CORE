<?php

namespace Devless\RulesEngine;

use App\Helpers\ActionClass;



class Rules
{


    private $assertion = [
        "elseWhenever" => false,
        "whenever"     => false,
        "ifAllFails"   => false,


    ];


    private $called = [
        "elseWhenever" => false,
        "whenever"     => false,
        "ifAllFails"   => false,


    ];


    private $results = '';


    /**
     * if equivalent
     * @param $assert
     * @return $this
     */
    public function whenever($assert)
    {
        $this->assertion['whenever'] = $assert;
        $this->called['whenever'] = true;

        return $this;
    }

    /**
     * elseif equivalent
     * @param $assert
     * @return $this
     */
    public function elseWhenever($assert)
    {
        $this->assertion['elseWhenever'] = $assert;
        $this->called['elseWhenever'] = true;

        return $this;
    }

    /**
     * else equivalent
     * @return $this
     */
    public function ifAllFails()
    {
        $this->assertion['ifAllFails'] =
            (!$this->assertion['elseWhenever'] || !$this->assertion['whenever']) ? : true;
           dd($this->assertion['ifAllFails']);


            return $this;
    }


    /**
     * Call on an ActionClass
     * @param $service
     * @param $method
     * @param null $params
     * @return mixed|string
     */
    public function run($service, $method, $params=null)
    {
        $whenever = $this->assertion['whenever'];
        $elseWhenever = $this->assertion['elseWhenever'];
        $ifAllFails = $this->assertion['ifAllFails'];

        $calledWhenever = $this->called['whenever'];
        $calledElseWhenever = $this->called['elseWhenever'];
        $calledIfAllFails = $this->called['ifAllFails'];



        $this->results = ($whenever || $elseWhenever || $ifAllFails )?
            ActionClass::execute($service, $method, $params):
            "You should have one complete flow in each rule ";

       return  ($this->called['ifAllFails'])? $this->results : $this;



    }



}




//voice out incase fails
