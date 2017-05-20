<?php

namespace Devless\RulesEngine;

use App\Helpers\DevlessHelper;
use App\Helpers\Helper;

class Rules
{
    use fillers, tableAuth, tableActions, flowControl, actions;

    private $assertion = [
        'elseWhenever' => false,
        'whenever' => false,
        'otherwise' => false,
    ];
    private $called = [
        'elseWhenever' => false,
        'whenever' => false,
        'otherwise' => false,
    ];
    public $results = '';
    private $answered = false;
    private $execOrNot = true;
    private $actionType = '';
    private $tableName = '';
    private $methodAction = [
        'GET' => 'query',
        'POST' => 'create',
        'PATCH' => 'update',
        'DELETE' => 'delete',
    ];
    public $accessRights = [
        'query' => 3,
        'create' => 3,
        'update' => 3,
        'delete' => 3,
    ];

    public $then = null;
    public $also = null;
    public $firstly = null;
    public $secondly = null;
    public $thirdly = null;
    public $beSureTo = null;
    public $lastly = null;


    public function __construct()
    {
        $this->then = $this->also = 
        $this->firstly = $this->secondly = 
        $this->thirdly = $this->beSureTo = 
        $this->lastly = $this;
    }

    public function requestType($requestPayload)
    {
        $tableName = DevlessHelper::get_tablename_from_payload($requestPayload);
        $actionType = $requestPayload['method'];
        $this->actionType = $actionType;
        $this->tableName = $tableName;

        return $this;
    }

    public function __call($method, $args)
    {
        if(!method_exists($this, $method))
        {
            $closest_method = 
                DevlessHelper::find_closest_word($method, get_class_methods($this));
            Helper::interrupt(642, 'There is no such method `'.$method.
                '` perharps you meant '.$closest_method. '?');
        }
    }

    /**
     * Execute callback functions with the chain.
     *
     * @param  $evaluator
     *
     * @return Rules|string
     */
    public function executor($evaluator)
    {
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
        } elseif ((($whenever) && $this->called['whenever'])
            || (($elseWhenever) && $this->called['whenever'] && $this->called['elseWhenever'])
            || ($otherwise && ($this->called['whenever'] || $this->called['elseWhenever']))
        ) {
            $evaluator();
        }

        return $this;
    }
}
