<?php

namespace Devless\RulesEngine;

trait math {
	public function sumUp()
	{
		if (!$this->execOrNot) {
                return $this;
        }

		$this->results = array_sum(func_get_args());
		return $this;
	}	
}