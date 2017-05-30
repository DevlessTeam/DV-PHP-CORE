<?php

namespace Devless\RulesEngine;

trait generators
{
	public function generateRandomInteger($length=100000000000000000000)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = rand(0, $length);
		return $this;
	}

	public function generateRandomAlphanums()
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	$this->results = $randomString;
		$this->results ;
		return $this;
	}

	public function generateRandomString($length=10)
	{
		if (!$this->execOrNot) {
            return $this;
        }
    	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        	$randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	$this->results = $randomString;
		return $this;
	}

	public function generateUnqiueId()
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = uniqid();
        return $this;	
	}
}