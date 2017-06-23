<?php

namespace Devless\RulesEngine;

trait math {

	/**
     * find the sum of numbers.
     *
     * @param integer $num
     *
     * @return $this
     */
	public function sumUp($num=0)
	{
		if (!$this->execOrNot) {
                return $this;
        }

		$this->results = array_sum(func_get_args());
		return $this;
	}	

	/**
     * subtract a bunch of numbers.
     *
     * @return $this
     */
	public function subtract()
	{
		if (!$this->execOrNot) {
            return $this;
        }

        if(count(func_get_args()) == 1){
             $this->results = ($this->results - func_get_args()[0]);
		} else {
        	foreach (func_get_args() as $key => $number) {
        		$total = ($key == 0)? $number : $total - $number;
        	}
        	$this->results = $total;
        }
		return $this;
	}

	/**
     * find the product of numbers.
     *
     * @return $this
     */
	public function multiply()
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = array_product(func_get_args());
        return $this;

	} 

	/**
     * divide a range of numbers.
     *
     * @return $this
     */
	public function divide()
	{
		if (!$this->execOrNot) {
            return $this;
        }
        
        if(count(func_get_args()) < 2){
        	$this->results = 0;
        	 return $this;
		} else {
        	foreach (func_get_args() as $key => $number) {
        		$total = ($key == 0)? $number : $total / $number;
        	}
        	$this->results = $total;
        }
		return $this;
	}	

    public function divideBy($number)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        
        $this->results = ($this->results)/$number;
        return $this;
    }
	/**
     * Find the square root of a number. eg:findSquareRoot($number)
     *
     * @param integer $num
     *
     * @return $this
     */
	public function findSquareRootOf($number)
	{
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = sqrt($number);
		return $this;
	}

    /**
     * Get the squareRoot of the result of the previous method eg: ->divide(20, 40)->getSquareRoot()->storeAs($output)->succeedWith($output)
     * return $this
     */
    public function getSquareRoot()
    {
        if(!$this->execOrNot) {
            return $this;
        }
        $this->results = sqrt($this->results);
        return $this;
    }
	/**
     * round up a number.
     *
     * @param integer $num
     *
     * @return $this
     */
	public function roundUp($number, $precision=1)
	{
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = round($number, $precision);
		return $this;
	}

    /**
     * Find the percent of a number example usage: find(10)->percentOf(200)
     * @param $number
     * @return $this
     */
    public function percentOf($number)
    {
        if(!$this->execOrNot) {
            return $this;
        }
        $percentage = ($this->results/100);
        $this->results = $percentage*($number);
        return $this;
    }
}
