<?php

namespace Devless\RulesEngine;

use App\Helpers\DevlessHelper;
use App\Helpers\Helper;

class Rules
{
    use fillers, tableAuth, devlessAuth, tableActions, flowControl, actions, mathLib, stringLib, dateLib, generators, mutateResponse, getResponse, collectionLib;

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
    
    public $status_code = 1000;
    public $message = '';
    public $payload = [];
    public $sharedStore = null;
    private $imports = [];
    private $importedClassInstance;
    

    private $stopAndOutputCalled = false;
    private $answered = false;
    private $execOrNot = true;
    private $onTableSet = false;
    private $isCurrentDBAction = false;
    private $onCurrentTable = false;
    private $actionType = '';
    private $tableName = '';
    private $selectedService = null;
    private $selectedMethod = null;
    private $onTableCalled = false;
    private $methodAction = [
        'GET' => 'query',
        'POST' => 'create',
        'PATCH' => 'update',
        'DELETE' => 'delete',
    ];

    public $EVENT = [];

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
    public $next = null;


    public function __construct()
    {
        $this->then = $this->also =
        $this->firstly = $this->secondly =
        $this->thirdly = $this->beSureTo =
        $this->next = $this->lastly = $this;
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
        if (!method_exists($this, $method)) {
            $imported_methods = array_keys($this->imports);
            if (!in_array($method, $imported_methods)) {
                $closestMethod =
                DevlessHelper::find_closest_word($method, array_merge(get_class_methods($this), $imported_methods));
                $failMessage = 'There is no such method `'.$method.'';
                $failMessage .= (strlen($closestMethod) > 0)? '` perharps you meant '.$closestMethod. '?' : '';
                Helper::interrupt(642, $failMessage);
            } else {
                if (!$this->execOrNot) {
                    return $this;
                }
                $className = $this->imports[$method];
                $this->results = $this->importedClassInstance[$className]
                       ->$method(...$args);
                return $this;
            }
        }
    }

     /**
     * use result from previous method as a param
     * if argument is not provided
     *
     * @param $args
     *
     * @return mix
     */
    public function useArgsOrPrevOutput($args)
    {
        return ($args == null)? $this->results : $args;
    }


    public function validateInputType($input)
    {
        if (!is_scalar($input) && !is_iterable($input)) {
            Helper::interrupt(1111, 'sorry but your data is not a string, boolean, decimals, integer or a collection ');
        }
    }

    public function cleanOutput()
    {
        if (is_array($this->results)) {
            $collection = $this->results;
            $newCollection = [];
            $this->objToArray($collection, $newCollection);
            $this->results = $newCollection;
        }
    }


    public function destroyVariable(&$input_var)
    {
        unset($input_var);
        return $this;
    }

    public function import($className)
    {
        foreach (func_get_args() as $className) {
            $classLocation = (strtolower($className) == config('devless')['name']) ?
                            config('devless')['system_class'] :
                            config('devless')['views_directory'].$className.'/ActionClass.php';
            $class = $this->getClassInstance($className, $classLocation);
            $methods = get_class_methods($class);
            $this->imports = array_fill_keys($methods, $className) + $this->imports ;
            $this->importedClassInstance[$className] = $class;
        }
        return $this;
    }

    private function getClassInstance($className, $classLocation)
    {
        if (!file_exists($classLocation)) {
            DevlessHelper::interrupt(604);
        }
        include_once($classLocation);

        return new $className();
    }
}
