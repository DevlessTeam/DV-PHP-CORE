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
        $collection = (is_null($collection))?[]:$collection;
        $arr = $this->objToArray($collection, $arr);
        $this->results = $arr;
        return $this;
	}
	public function collect($collection)
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->fromTheCollectionOf($collection);
		return $this;
	}
	public function getValuesWithoutKeys()
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->results = collect($this->results)->flatten()->toArray();
		return $this;
	}
	public function getAllKeys()
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->results = (is_array($this->results[0]))?$this->results[0]:$this->results;
		
		$this->results = collect($this->results)->flip()->flatten()->toArray();
		return $this;	
	}
	public function getFirstElement()
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->results = collect($this->results)->first();
		return $this;
	}
	public function getElement($nth)
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->results = (isset($this->results[$nth-1]))
			?$this->results[$nth-1]:[];
		return $this;
	}
	public function getLastElement()
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->results = collect($this->results)->last();
		return $this;
	}
	public function countTheNumberOfElements()
	{
		if (!$this->execOrNot) {
            return $this;
        }
		$this->results = collect($this->results)->count();
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
        $this->results = array_column($this->results, $keys);
	
        return $this;
	}

	public function onTheCollectionApplyMethod($method, $key=null)
	{
		if (!$this->execOrNot) {
            return $this;
        }
        $params = func_get_args();
        unset($params[0], $params[1]);
        dd($params);
        $input = $this->results;
        for($i=0; $i < count($input); $i++){
     		$this->$method($input[$i]->$key);
     		$mutatedValue = $this->results;
     		$input[$i]->$key = $mutatedValue;
     	}
     	$this->results = $input;
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
	public function objToArray($obj, &$arr)
	{
		if (!$this->execOrNot) {
            return $this;
        }
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
//withTheCollection()
//collapse array
//removeAllcollectionKeys =>flatten
//removeAllCollectionValues
//getElementWithPair() =>
//countElementsInCollection
//findTheDifferenceBetweenCollections()
//removeTheKeys()
//first, second , last, third nth()
//forEachValue('ConvertTOUpperCase')
//forEachValueWithKey('ConvertToUpperCase', 'names')
//SwitchKeyValuePosition()
//groupBy
//joinAll('products', '-')
//isEmpty
//isNotEmpty 
//findTheMax()
//findTheMin()
//findTheMode()
//appendTheCollection()
//prependTheCollection()
//removeTheLastElement()
//randomizeTheCollection()
//reverseCollection()
//mergeValuesWithTheMatchingKey('name', 'edmond')
//sortBy
//offsetCollection
//getAnNmberOfThe()
//partitionCollection
//SumUpCollectionAtKey()/
//returnUnqiueValueBasedonTheKey()
//whereIn()
//WhereNotIn
