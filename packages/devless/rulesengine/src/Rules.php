<?php
namespace Devless\RulesEngine;
use App\Helpers\Helper;
use App\Helpers\ActionClass;
use App\Helpers\DevlessHelper;
class Rules
{
    private $assertion = [
        'elseWhenever' => false,
        'whenever'     => false,
        'otherwise'   => false,
    ];
    private $called = [
        'elseWhenever' => false,
        'whenever'     => false,
        'otherwise'   => false,
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
        'DELETE' => 'delete'
    ];
    public $accessRights = [
        'query'  =>  3,
        'create' => 3,
        'update' => 3,
        'delete' => 3
    ];

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
        $this->execOrNot = ($this->actionType == 'GET');
        return $this ;
    }
    /**
     * Check if data is being updated on table
     * @return $this
     */
    public function onUpdate()
    {
        $this->execOrNot = ($this->actionType == 'PATCH');
        return $this ;
    }
    /**
     * Check if data is being added to table
     * @return $this
     */
    public function onCreate()
    {
        $this->execOrNot = ($this->actionType == 'POST');
        return $this ;
    }
    /**
     * Check if data is being deleted from table
     * @return $this
     */
    public function onDelete()
    {
        $this->execOrNot = ($this->actionType == 'DELETE');
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
        $this->execOrNot = ($assert);
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
        $this->execOrNot = $this->assertion['otherwise'] =
            (!$this->assertion['whenever']) ? : false;
         
        if (!$this->execOrNot) {
            return $this;
        }
        $this->assertion['elseWhenever'] = $assert;
        $this->called['elseWhenever'] = true;
        $this->execOrNot = ($assert);
        return $this;
    }
    /**
     * equivalence of else
     *
     * @return $this
     */
    public function otherwise()
    {
        $this->execOrNot = $this->assertion['otherwise'] =
            (!$this->assertion['elseWhenever'] && !$this->assertion['whenever']) ? : false;
        
        if (!$this->execOrNot) {
            return $this;
        }
        $this->called['otherwise'] = true;
        $this->execOrNot = $this->assertion['otherwise'];
        return $this;
    }
    /**
     * check if on intended table
     *
     * @param  string $expectedTableName
     * @return mixed|string
     */
    public function onTable($expectedTableName)
    {

        if (!$this->execOrNot) {
            return $this;
        }

        $this->tableName = (is_array($this->tableName))? $this->tableName[0]:$this->tableName;
        $this->execOrNot = ($this->tableName == $expectedTableName);
        return $this;
    }

    /**
     * Convert table access right to authenticated
     *
     * @return instance
     */
    public function authenticateUser()
    {

        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 2;
        return $this;
    }

    /**
     * Lock down table access
     *
     * @return instance
     */
    public function lockDownTable()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 0;
        return $this;
    }

    /**
     * Open Up table access to everyone 
     *
     * @return instance
     */
    public function providePublicAccess()
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $action = $this->methodAction[$this->actionType];
        $this->accessRights[$action] = 1;
        return $this;
    }

    /**
     * Stop execcution with an exception
     *
     * @param  null $msg
     * @return mixed|string
     */
    public function succeedWith($msg = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $evaluator = function () use ($msg) {
            return Helper::interrupt(1000, $msg);
        };
        return $this->executor($evaluator);
    }

    /**
     * Stop execution with an exception
     *
     * @param  null $msg
     * @return mixed|string
     */
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
    public function run($service, $method, $params = null, $remoteUrl = null, $token = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $evaluator = function () use ($service, $method, $params, $remoteUrl, $token) {
            if ($remoteUrl && $token) {
                $this->results = ActionClass::remoteExecute($service, $method, $params, $remoteUrl, $token);
            } else {
                $this->results = ActionClass::execute($service, $method, $params);
            }
            $this->answered = true;
            return true;
        };
        return $this->executor($evaluator);
    }
    /**
     * Get results variable and set to variable
     * @param $input_var
     * @return $this
     */
    public function getRunResult(&$input_var)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $input_var = $this->results;
        return $this;
    }
    /**
     * Execute callback functions with the chain
     *
     * @param  $evaluator
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
        } elseif (( ($whenever ) && $this->called['whenever'])
            || (($elseWhenever ) && $this->called['whenever'] && $this->called['elseWhenever'])
            || ($otherwise && ( $this->called['whenever'] || $this->called['elseWhenever'] ))
        ) {
            $evaluator();
        }
        return $this;
    }
}