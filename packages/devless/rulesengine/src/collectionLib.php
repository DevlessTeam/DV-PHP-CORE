<?php 

namespace Devless\RulesEngine;


trait collectionLib
{

	public function fromTheCollectionOf($collection)
	{
		if (!$this->execOrNot) {
            return $this;
        }

        $this->results = $collection;
        return $this;
	}

	public function fetchAllWith($key, $value)
	{
		if (!$this->execOrNot) {
            return $this;
        }

        if($this->isAssoc($this->results)){
        	$this->results = collect($this->results)->where($key, $value)->all();
		} else {
			$newArray = [];
			foreach($this->results as $singleObj){
				if($singleObj->$key == $key)
				{
					$newArray[] = (array)$singleObj;
				}
			
			}
			$this->results = $newArray;
		}
        return $this;

	}

	public function fetchOnly($keys)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        if($this->isAssoc($this->results)){
        	$this->results = collect($this->results)->only($keys)->all();
		} else {
			$newArray = [];
			foreach($this->results as $singleObj){
				$newArray[] = collect( (array)$singleObj )
				->only($keys)->all();
			}
			$this->results = $newArray;
		}
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

		
}




