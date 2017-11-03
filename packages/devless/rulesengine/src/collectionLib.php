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
		$this->cleanOutput();
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
		$this->cleanOutput();
		return $this;
	}
	public function getAllKeys()
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$this->results = (is_array($this->results[0]))?$this->results[0]:$this->results;

		$this->results = collect($this->results)->flip()->flatten()->toArray();
		$this->cleanOutput();
		return $this;	
	}
	public function getFirstElement()
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$this->results = collect($this->results)->first();
		$this->cleanOutput();
		return $this;
	}

	public function appendCollectionTo($superArray, $subArray, $superKey, $subKey, 
		$resultingKey="merged_result", $addToRelated=false)
	{	
		if (!$this->execOrNot) {
			return $this;
		}

		$superArray = collect($superArray)->all();
		$subArray = collect($subArray)->all();
		
		for ($count=0; $count < count($superArray) ; $count++) { 
			foreach ($subArray as $singleObj) {
				if( !isset($superArray[$count][$resultingKey]) && !$addToRelated ){$superArray[$count][$resultingKey] = [];}
				if(!isset($superArray[$count]['related'])){$superArray[$count]['related'] = [];}
				if(!isset($superArray[$count]['related'][$resultingKey]) && $addToRelated ){$superArray[$count]['related'][$resultingKey] = [];}
				if($superArray[$count][$superKey] == $singleObj[$subKey]){
					($addToRelated)?array_push($superArray[$count]['related'][$resultingKey], $singleObj):
					array_push($superArray[$count][$resultingKey],$singleObj);
				}
			}
		}
		$this->results = collect($superArray);;
		$this->cleanOutput();
		return $this;
	}

	public function appendCollectionToRelated($superArray, $subArray, $superKey, $subKey, $resultingKey) 
	{       
		$this->appendCollectionTo($superArray, $subArray, $superKey, $subKey, $resultingKey, true);
		return $this; 
	}

	public function getElement($nth)
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$this->results = (isset($this->results[$nth-1]))
		?$this->results[$nth-1]:[];
		$this->cleanOutput();
		return $this;
	}
	public function getLastElement()
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$this->results = collect($this->results)->last();
		$this->cleanOutput();
		return $this;
	}
	public function countTheNumberOfElements($collection = null)
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$this->results = collect($this->useArgsOrPrevOutput())->count();
		$this->cleanOutput();
		return $this;
	}
	public function fetchAllWith($key, $value)
	{
		if (!$this->execOrNot) {
			return $this;
		}

		$this->results = collect($this->results)->where($key, $value)->all();
		$this->cleanOutput();
		return $this;
	}
	public function fetchOnly($keys)
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$this->results = array_column($this->results, $keys);
		$this->cleanOutput();
		return $this;
	}

	public function onTheCollectionApplyMethod($method, $key=null, $newKey=null)
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$params = func_get_args();
		unset($params[0], $params[1]);
		$input = $this->results;
		for($i=0; $i < count($input); $i++){
			if($key) {
				$this->$method($input[$i][$key]);
			} else {
				$this->$method($input[$i]);
			}
			$mutatedValue = $this->results;
			if($newKey) {
				$input[$i][$newKey] = $mutatedValue;
			} else {
				$input[$i][$key] = $mutatedValue;
			}
		}
		$this->results = $input;
		$this->cleanOutput();
		return $this;
	}

	public function forEachOneIn($items, $script, $variables=[])
	{

		if (!$this->execOrNot) {
			return $this;
		}
		$eachOne = $items;
		foreach ($eachOne as $key => $value) {
			extract($variables);
			eval('$this->'.$script.';');

		}
		$this->results = $eachOne;
		$this->cleanOutput();
		$this->execOrNot = true;
		return $this;

	}

	public function countElementsInCollection($collection=null)
	{
		if (!$this->execOrNot) {
			return $this;
		}
		if(is_string($this->results)){
			$this->results = 0;
		} else {
			$this->results = count($this->useArgsOrPrevOutput($collection));
		}
		$this->cleanOutput();
		return $this;
	}

	public function reverseTheCollection($collection=null)
	{
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = array_reverse($this->useArgsOrPrevOutput($collection));
		$this->cleanOutput();
		return $this;
	}
	public function sortCollectionBy($key)
	{
		if(!$this->execOrNot) {
			return $this;
		}

		$this->results = collect($this->results)->sortBy($key);
		$this->cleanOutput();
		return $this;
	}

	public function offsetCollectionBy($offset)
	{
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = array_splice($this->results, $offset);
		$this->cleanOutput();
		return $this;
	}

	public function reduceNumberOfElementsTo($size)
	{
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = array_splice($this->results, 0, $size);
		$this->cleanOutput();
		return $this;
	}

	public function paginateCollection($offset, $size)
	{
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = array_splice($this->results, $offset, $size);
		$this->cleanOutput();
		return $this;
	}

	public function findCollectionDiffs($collectionOne, $collectionTwo) {
		
		if(!$this->execOrNot) {
			return $this;
		}
		$this->results = collect($collectionOne)->diff(collect($collectionTwo));
		$this->cleanOutput();
		return $this;

	}

	public function expandCollection($collection) 
	{
		if (!$this->execOrNot) {
			return $this;
		}
		$output = [];
		$template = [];
		(is_array($collection) && $this->isAssoc($collection))? '':$this->failWith('Sorry but you need to pass in a key value pair `->using(["category"=>category])->mock(template)`');

		foreach ($collection as $key => $data) {
			$push_to_array  = function() use (&$output, &$eachValue, &$template, &$key ) {
				$innerTemplate = $template;
				$innerTemplate[$key] = $eachValue;
				$output[] = $innerTemplate;

			};
			if( !is_scalar($data) ) {
				
				foreach ($data as $index => $eachValue) {
					(isset($output[$index]))? 
					$output[$index][$key] = $eachValue :
					$push_to_array();

				}	
			} else {
					for($i=0; $i< count($output); $i++) {
						$output[$i][$key] = $data;
					}
			}
		}
		$this->results = $output;
		return $this;
	}


	public function addAnElementToCollection($element)
	{
		//
	}

	public function removeElementFromCollection($key)
	{
		//
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

