<?php

namespace Devless\RulesEngine;

trait string
{
	/**
	 * Concatenate string together
	 * @param ..params
	 * @return $this
	 * */
	public function concatenate()
	{
		if (!$this->execOrNot) {
                return $this;
        }
		$strings = func_get_args();
		$this->results = implode("", $strings);
		return $this;

	}

	/**
	 * Get first character
	 * @param $string
	 * @return $this
	 * */
	public function getFirstCharacter($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[0];
		return $this;
	}

	/**
	 * Get second character
	 * @param $string
	 * @return $this
	 * */
	public function getSecondCharacter($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[1];
		return $this;
	}

	/**
	 * Get third character
	 * @param $string
	 * @return $this
	 * */
	public function getThirdCharacter($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[2];
		return $this;
	}

	/**
	 * Get last character character
	 * @param $string
	 * @return $this
	 * */
	public function getLastCharacter($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }

		$string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[strlen($string)-1];
        return $this;
	}

	/**
	 * Get last but one character
	 * @param $string
	 * @return $this
	 * */
	public function getLastButOneCharacter($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[strlen($string)-2];
        return $this;
	}

	public function reverseString($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = strrev($string);
        return $this;
	}

	/**
	 * replace a string with another
	 * @param $string
	 * @return $this
	 * */
	public function findNReplace($string, $replacement, $subject)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = str_replace($string, $replacement, $subject);
		return $this;
	}

	/**
	 * change string to uppercase
	 * @param $string
	 * @return $this
	 * */
	public function convertToUpperCase($string=null)
	{
	
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = strtoupper($string);
		return $this;
	}

	/**
	 * change string to lowercase
	 * @param $string
	 * @return $this
	 * */
	public function convertToLowerCase($string=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = strtolower($string);
		return $this;
	}
}



