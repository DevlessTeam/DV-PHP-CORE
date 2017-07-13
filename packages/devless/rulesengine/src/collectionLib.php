<?php 

namespace Devless\RulesEngine;


trait collectionLib
{

	public function fromTheCollectionOf($collection)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $arr = [];
        $arr = $this->objToArray($collection, $arr);
        $this->results = $arr;
        return $this;
	}

	public function fetchAllWith($key, $value)
	{
		if (!$this->execOrNot) {
            return $this;
        }
    
   		$this->results = collect($this->results)->where($key, $value)->all();
		
        return $this;

	}

	public function fetchOnly($keys)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $this->results = array_column($this->results, 'email');
	
        return $this;

	}


	public function isAssoc(array $arr)
	{
		if (!$this->execOrNot) {
            return $this;
        }

	    if (array() === $arr) return false;
	    return array_keys($arr) !== range(0, count($arr) - 1);
	}

	public function objToArray($obj, &$arr){

	    if(!is_object($obj) && !is_array($obj)){
	        $arr = $obj;
	        return $arr;
	    }

	    foreach ($obj as $key => $value)
	    {
	        if (!empty($value))
	        {
	            $arr[$key] = array();
	            $this->objToArray($value, $arr[$key]);
	        }
	        else
	        {
	            $arr[$key] = $value;
	        }
	    }
	    return $arr;
	}
		
}




