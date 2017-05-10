<?php

namespace Devless\RulesEngine;

use App\Helpers\Helper;
use App\Helpers\ActionClass;
use App\Helpers\Response as Response;

trait actions
{
    /**
     * check if on intended table.
     *
     * @param string $expectedTableName
     *
     * @return mixed|string
     */
    public function onTable($expectedTableName)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $this->tableName = (is_array($this->tableName)) ? $this->tableName[0] : $this->tableName;
        $this->execOrNot = ($this->tableName == $expectedTableName);

        return $this;
    }

    /**
     * Stop execcution with an exception.
     *
     * @param null $msg
     *
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
     * Stop execution with an exception.
     *
     * @param null $msg
     *
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
     * Call on an ActionClass.
     *
     * @param  $service
     * @param  $method
     * @param null $params
     *
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
     * Get results variable and set to variable.
     *
     * @param $input_var
     *
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
}
