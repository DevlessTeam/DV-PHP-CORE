<?php

use Devless\RulesEngine\Rules;

class deferRunner
{
    public $rules = null;

    public function __construct()
    {
        $this->rules = new Rules();
    }
    public function run()
    {
        $params = func_get_args();
        $method = $params[0];
        $this->rules->$method(...$params[1]);
    }
}
