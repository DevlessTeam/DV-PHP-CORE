<?php

namespace Devless\RulesEngine;

trait stringLib
{
    /**
     * Concatenate strings together eg: `->beforeCreating()->concatenate("user_",$input_name)`
     * @param n number of params
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
     * Get first character eg: `->beforeCreating()->getFirstCharacter("Hello")->storeAs($first_char)->succeedWith($first_char)`
     * @param $string
     * @return $this
     * */
    public function getFirstCharacter($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[0];
        return $this;
    }

    /**
     * Get second character eg: `->beforeCreating()->getSecondCharacter("Hello")->storeAs($second_char)->succeedWith($second_char)`
     * @param $string
     * @return $this
     * */
    public function getSecondCharacter($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[1];
        return $this;
    }

    /**
     * Get third character eg: `->beforeCreating()->getThirdCharacter("Hello")->storeAs($third_char)->succeedWith($third_char)`
     * @param $string
     * @return $this
     * */
    public function getThirdCharacter($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[2];
        return $this;
    }

    /**
     * Get nth character eg: `->beforeCreating()->getCharacter(5, "Hello")->storeAs($nth_char)->succeedWith($nth_char)`
     * @param $nth integer
     * @param $string
     * @return $this
     * */
    public function getCharacter($nth, $string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[$nth];
        return $this;
    }

    /**
     * Get last character eg: `->beforeCreating()->getLastCharacter("Hello")->storeAs($last_char)->succeedWith($last_char)`
     * @param $string
     * @return $this
     * */
    public function getLastCharacter($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[strlen($string)-1];
        return $this;
    }

    /**
     * Get last but one character eg: `->beforeCreating()->getLastButOneCharacter("Hello")->storeAs($last_but_one_char)->succeedWith($last_but_one_char)`
     * @param $string
     * @return $this
     * */
    public function getLastButOneCharacter($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = $string[strlen($string)-2];
        return $this;
    }
    /**
     * Reverse a string eg: ->beforeQuerying()->assign("nan")->to($string)->reverseString()->storeAs($reverseString)
 ->whenever(assertIts::equal($string, $reverseString))->succeedWith("Its a palindrome :)")
 ->otherwise()->failWith("Its not a palindrom :(")
    @param $string
    @return $this
     */
    public function reverseString($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = strrev($string);
        return $this;
    }

    /**
     * replace a string with another eg `->beforeCreating()->findNReplace("{{name}}", $input_name, $input_message)->storeAs($input_message)`
     * @param $string
     * @param $replacement
     * @param $subject
     * @return $this
     * */
    public function findNReplace($string, $replacement, $subject)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = str_replace($string, $replacement, $subject);
        $this->cleanOutput();
        return $this;
    }

    /**
     * change string to uppercase eg: `->beforeCreating()->convertToUpperCase($input_name)->storeAs($input_name)`
     * @param $string
     * @return $this
     * */
    public function convertToUpperCase($string = null)
    {
    
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = strtoupper($string);
        $this->cleanOutput();
        return $this;
    }

    /**
     * change string to lowercase eg: `->beforeCreating()->convertToLowerCase($input_name)->storeAs($input_name)`
     * @param $string
     * @return $this
     * */
    public function convertToLowerCase($string = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $this->results = strtolower($string);
        $this->cleanOutput();
        return $this;
    }

    /**
     * Truncate a string to some length eg `->beforeCreating()->truncateString(4, $input_desc)->getResults($trucatedString)->storeAs($stub)`
     * @param $len
     * @param $string
     * @param $trimMaker
     * @return $this
     * */
    public function truncateString($len, $string = null, $trimMaker = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $string = $this->useArgsOrPrevOutput($string);
        $trimMaker = ($trimMaker != null)?$trimMaker:'...';
        $this->results = mb_strimwidth($string, 0, $len, $trimMaker);
        $this->cleanOutput();
        return $this;
    }

    /**
     * Count the number of words in a sentence eg: `->beforeCreating()->onTable('users')->countWords($input_description)->storeAs($desc_length)->whenever($desc_length <= 5)->failWith("Your product description is very short")`
     * @param $sentence
     * @return $this
     */
    public function countWords($sentence)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $sentence = $this->useArgsOrPrevOutput($sentence);
        $this->results = str_word_count($sentence);
        $this->cleanOutput();
        return $this;
    }

    /**
     * Find the number of characters in a word or sentence eg: `->beforeCreating()->onTable('users')->countCharacters($input_name)->storeAs($name_length)->whenever($name_length <= 0)->failWith("name seems to be empty")`
     * @param word
     * @return $this
     */
    public function countCharacters($word)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $word = $this->useArgsOrPrevOutput($word);
        $this->results = strlen($word);
        $this->cleanOutput();
        return $this;
    }
}
