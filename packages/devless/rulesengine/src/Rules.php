<?php

namespace Devless\RulesEngine;

use App\Helpers\ActionClass;
use App\Helpers\Helper;
use App\Helpers\Response;


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
    private $answered = false;


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
            (!$this->assertion['elseWhenever'] || !$this->assertion['whenever']) ? : false;
        $this->called['ifAllFails'] = true;
            return $this;
    }

    public function succeedWith($msg=null)
    {
        $evaluator = function() use($msg) {
          return Response::respond(1001, $msg);
        };

        return $this->executor($evaluator);

    }

    public function failWith($msg=null)
    {
       $evaluator = function() use($msg) {
           return Helper::interrupt(1001, $msg);
       };

       return $this->executor($evaluator);
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

        $evaluator = function () use ($service, $method, $params) {

            $results = ActionClass::execute($service, $method, $params);
            $this->answered = true;
            return $results;
        };

        return $this->executor($evaluator);

    }


    /**
     * Execute callback functions with the chain
     * @param $evaluator
     * @return Rules|string
     */
    public function executor($evaluator)
    {
        $whenever = $this->assertion['whenever'];
        $elseWhenever = $this->assertion['elseWhenever'];
        $ifAllFails = $this->assertion['ifAllFails'];

        $error = function($msg='you cannot call on elseWhenever without calling on whenever') {
            $this->answered = true;
            $this->results = $msg;

        };

        if ($this->called['elseWhenever'] && !$this->called['whenever']) {
            $error();

        } elseif($ifAllFails && !$this->called['whenever'] ) {
            $msg = 'You cannot call on else without calling on whenever';
            $error($msg);

        } elseif((($whenever && !$this->answered) && $this->called['whenever']) ||
            (($elseWhenever && !$this->answered) && $this->called['whenever'] && $this->called['elseWhenever']) ||
            ($ifAllFails && !$this->answered && ( $this->called['whenever'] || $this->called['elseWhenever'] ))) {

            $this->results =  $evaluator();

        }

        return ($this->called['ifAllFails'])? $this->results : $this;

    }



}
