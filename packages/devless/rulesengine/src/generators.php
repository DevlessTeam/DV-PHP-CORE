<?php

namespace Devless\RulesEngine;

trait generators
{
    /**
     * generates random integers. This may be used for invitation or promotional code generation. eg `->beforeCreating()->generateRandomInteger(10)->storeAs($promo_code)->assign($promo_code)->to($input_promo)`
     * @param $length
     * @return $this
     */
	public function generateRandomInteger($length=100000000000000000000)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = rand(0, $length);
		return $this;
	}

    /**
     * generates random alphanumeric values. This may be used for generating order Ids. eg `->beforeCreating()->generateRandomAlphanums()->storeAs($order_id)->assign($order_id)->to($input_order_id)`
     * @return $this
     */
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

    /**
     * generates random string.This generates random string codes. eg `->beforeCreating()->generateRandomInteger(10)->storeAs($promo_code)->assign($promo_code)->to($input_promo)`
     * @param $length
     * @return $this
     */
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

    /**
     * generates unique Id.This generates unique Id . eg `->beforeCreating()->generateUniqueId()->storeAs($user_id)->assign($user_id)->to($input_id)`
     * @param $length
     * @return $this
     */    
	public function generateUniqueId()
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = uniqid();
        return $this;	
	}
}