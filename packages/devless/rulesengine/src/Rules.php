<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;
use App\Helpers\ActionClass;
use App\Helpers\DevlessHelper;

class Rules
{


    private $assertion = [
        "elseWhenever" => false,
        "whenever"     => false,
        "otherwise"   => false,


    ];


    private $called = [
        "elseWhenever" => false,
        "whenever"     => false,
        "otherwise"   => false,


    ];


    public $results = '';
    private $answered = false;

    private $execOrNot = true;
    private $actionType = '';

    private $tableName = '';

    public function requestType($requestPayload)
    {
        $tableName = DevlessHelper::get_tablename_from_payload($requestPayload);
        $actionType = $requestPayload['method'];

        $this->actionType = $actionType;
        $this->tableName  = $tableName;
        return $this;
    }


    /**
     * Check if table is being queried
     * @return $this
     */
    public function onQuery()
    {
        $this->execOrNot = ($this->actionType == 'GET')? true : false;
        return $this ;
    }


    /**
     * Check if data is being updated on table
     * @return $this
     */
    public function onUpdate()
    {
        $this->execOrNot = ($this->actionType == 'PATCH')? true : false;
        return $this ;
    }


    /**
     * Check if data is being added to table
     * @return $this
     */
    public function onCreate()
    {
        $this->execOrNot = ($this->actionType == 'POST')? true : false;
        return $this ;
    }


    /**
     * Check if data is being deleted from table
     * @return $this
     */
    public function onDelete()
    {
        $this->execOrNot = ($this->actionType == 'PATCH')? true : false;
        return $this ;
    }

    /**
     * equivalence of if
     *
     * @param  $assert
     * @return $this
     */
    public function whenever($assert)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->assertion['whenever'] = $assert;
        $this->called['whenever'] = true;

        return $this;
    }

    /**
     * equivalence of elseif
     *
     * @param  $assert
     * @return $this
     */
    public function elseWhenever($assert)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->assertion['elseWhenever'] = $assert;
        $this->called['elseWhenever'] = true;

        return $this;
    }

    /**
     * equivalence of else
     *
     * @return $this
     */
    public function otherwise()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->assertion['otherwise'] =
            (!$this->assertion['elseWhenever'] || !$this->assertion['whenever']) ? : false;
        $this->called['otherwise'] = true;
            return $this;
    }

    public function onTable($expectedTableName)
    {
        $this->execOrNot = ($this->tableName == $expectedTableName)? true:false;
        return $this;
    }

    public function succeedWith($msg = null)
    {
        $evaluator = function () use ($msg) {
            return Helper::interrupt(1000, $msg);
        };

        return $this->executor($evaluator);

    }

    public function failWith($msg = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $evaluator = function () use ($msg) {
            return Helper::interrupt(1001, $msg);
        };

        return $this->executor($evaluator);
    }


    /**
     * Call on an ActionClass
     *
     * @param  $service
     * @param  $method
     * @param  null    $params
     * @return mixed|string
     */
    public function run($service, $method, $params = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $evaluator = function () use ($service, $method, $params) {

            $results = ActionClass::execute($service, $method, $params);
            $this->answered = true;
            return $results;
        };
        return $this->executor($evaluator);

    }


    /**
     * Execute callback functions with the chain
     *
     * @param  $evaluator
     * @return Rules|string
     */
    public function executor($evaluator)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $whenever = $this->assertion['whenever'];
        $elseWhenever = $this->assertion['elseWhenever'];
        $otherwise = $this->assertion['otherwise'];

        $error = function ($msg = 'you cannot call on elseWhenever without calling on whenever') {
            $this->answered = true;
            $this->results = $msg;

        };

        if ($this->called['elseWhenever'] && !$this->called['whenever']) {
            $error();
        } elseif ($otherwise && !$this->called['whenever']) {
            $msg = 'You cannot call on otherwise without calling on whenever';
            $error($msg);
        } elseif ((($whenever && !$this->answered) && $this->called['whenever'])
            || (($elseWhenever && !$this->answered) && $this->called['whenever'] && $this->called['elseWhenever'])
            || ($otherwise && !$this->answered && ( $this->called['whenever'] || $this->called['elseWhenever'] ))
        ) {
            $this->results =  $evaluator();
        }

        return ($this->called['otherwise'])? $this->results : $this;

    }
}
