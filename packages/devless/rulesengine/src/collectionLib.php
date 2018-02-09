<?php
namespace Devless\RulesEngine;

trait collectionLib
{
    /**
     *convert an array into a collection eg: `->beforeCreating()->fromTheCollectionOf(["name"=>"mike", "age"=>29])->getAllKeys()->storeAs($keys)->stopAndOutput(1000, "got response", $keys) #["name", "age"]`
     *
     * @param $collection
     *
     * @return $this
     */
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

    /**
     *convert an array into a collection eg: `->beforeCreating()->collect(["name"=>"mike", "age"=>29])->getAllKeys()->storeAs($keys)->stopAndOutput(1000, "got response", $keys) #["name", "age"]`
     *
     * @param $collection
     *
     * @return $this
     */
    public function collect($collection)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->fromTheCollectionOf($collection);
        return $this;
    }

    /**
     *gets all the values in a collection eg: `->beforeCreating()->collect(["name"=>"mike", "age"=>29])->getValuesWithoutKeys()->storeAs($values)->stopAndOutput(1000, "got response", $values) # ["mike",29]`
     *
     * @param $collection
     *
     * @return $this
     */
    public function getValuesWithoutKeys($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = collect($collection)->flatten()->toArray();
        $this->cleanOutput();
        return $this;
    }

    /**
     *convert an array into a collection eg: `->beforeCreating()->fromTheCollectionOf(["name"=>"mike", "age"=>29])->getAllKeys()->storeAs($keys)->stopAndOutput(1000, "got response", $keys) #["name", "age"]`
     *
     * @param $collection
     *
     * @return $this
     */
    public function getAllKeys($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $collection = (is_array($collection))?$collection:$collection;

        $this->results = collect($collection)->flip()->flatten()->toArray();
        $this->cleanOutput();
        return $this;
    }

     /**
     *get the first element in a collection eg: `->beforeCreating()->fromTheCollectionOf(["name"=>"mike", "age"=>29])->getFirstElement()->storeAs($element)->stopAndOutput(1000, "got response", $element) #["mike"]`
     *
     * @param $collection
     *
     * @return $this
     */
    public function getFirstElement($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = collect($collection)->first();
        $this->cleanOutput();
        return $this;
    }

    /**
     *Match up and pair collections eg: `->beforeCreating()->collect(["name"=>"mike", "age"=>29])->appendCollectionTo($superArray=[["id"=>1,"name"=>"sam"],["id"=>2,"name"=>"josh"]], $subArray=[["id"=>2,"age"=>20],["id"=>1,"age"=>12]], $superKey="id",$subKey="id", $resultingKey="result" )->storeAs($element)->stopAndOutput(1000, "got response", $element) `
     *
     * @param $superArray
     * @param $subArray
     * @param $superKey
     * @param $subKey
     * @param $resultingKey
     * @param $addToRelated
     *
     * @return $this
     */
    public function appendCollectionTo(
        $superArray,
        $subArray,
        $superKey,
        $subKey,
        $resultingKey = "merged_result",
        $addToRelated = false
    ) {
        if (!$this->execOrNot) {
            return $this;
        }

        $superArray = collect($superArray)->all();
        $subArray = collect($subArray)->all();
        
        for ($count=0; $count < count($superArray); $count++) {
            foreach ($subArray as $singleObj) {
                if (!isset($superArray[$count][$resultingKey]) && !$addToRelated) {
                    $superArray[$count][$resultingKey] = [];
                }
                if (!isset($superArray[$count]['related'])) {
                    $superArray[$count]['related'] = [];
                }
                if (!isset($superArray[$count]['related'][$resultingKey]) && $addToRelated) {
                    $superArray[$count]['related'][$resultingKey] = [];
                }
                if ($superArray[$count][$superKey] == $singleObj[$subKey]) {
                    ($addToRelated)?array_push($superArray[$count]['related'][$resultingKey], $singleObj):
                    array_push($superArray[$count][$resultingKey], $singleObj);
                }
            }
        }
        $this->results = collect($superArray);
        ;
        $this->cleanOutput();
        return $this;
    }

    /**
     *match up and pair collections but store results in related. eg: `->beforeCreating()->collect(["name"=>"mike", "age"=>29])->appendCollectionToRelated($superArray=[["id"=>1,"name"=>"sam"],["id"=>2,"name"=>"josh"]], $subArray=[["id"=>2,"age"=>20],["id"=>1,"age"=>12]], $subArray="id",$subKey="id", $resultingKey="result" )->storeAs($element)->stopAndOutput(1000, "got response", $element) `
     *
     * @param $superArray
     * @param $subArray
     * @param $superKey
     * @param $subKey
     * @param $resultingKey
     * @param $addToRelated
     *
     * @return $this
     */
    public function appendCollectionToRelated($superArray, $subArray, $superKey, $subKey, $resultingKey)
    {
        $this->appendCollectionTo($superArray, $subArray, $superKey, $subKey, $resultingKey, true);
        return $this;
    }

     /**
     *get the nth element in a collections eg: `->beforeCreating()->collect(["Joe", "Sam", "Mike"])->getElement(1)->storeAs($element)->stopAndOutput(1000, "got response", $element) #Joe`
     *
     * @param $nth
     * @param $collection
     *
     * @return $this
     */
    public function getElement($nth, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $collection = (isset($collection[$nth-1]))
        ?$collection[$nth-1]:[];
        $this->results = $collection;
        $this->cleanOutput();
        return $this;
    }

    /**
     *get the last element in a collections eg: `->beforeCreating()->collect(["Joe", "Sam", "Mike"])->getLastElement()->storeAs($element)->stopAndOutput(1000, "got response", $element) #Mike`
     *
     * @param $collection
     *
     * @return $this
     */
    public function getLastElement($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = collect($collection)->last();
        $this->cleanOutput();
        return $this;
    }

    /**
     *count the number of elements in a collections eg: `->beforeCreating()->collect(["Joe", "Sam", "Mike"])->countTheNumberOfElements()->storeAs($count)->stopAndOutput(1000, "got response", $count) #3`
     *
     * @param $collection
     *
     * @return $this
     */
    public function countTheNumberOfElements($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $this->results = collect($this->useArgsOrPrevOutput($collection))->count();
        $this->cleanOutput();
        return $this;
    }

    /**
     *Fetch all elements whos key are of a particular value eg: `->beforeCreating()->collect([["item"=>"soap", "quantity"=>5],["item"=>"milk", "quantity"=>3],["item"=>"book", "quantity"=>5]])->fetchAllWith("quantity", 5)->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #[["item"=>"soap", "quantity"=>5],["item"=>"book", "quantity"=>5]]`
     *
     * @param $key
     * @param $value
     * @param $collection
     *
     * @return $this
     */
    public function fetchAllWith($key, $value, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $this->results = collect($this->useArgsOrPrevOutput($collection))->where($key, $value)->all();
        $this->cleanOutput();
        return $this;
    }

    /**
     *Fetch all elements whos key are of a particular value eg: `->beforeCreating()->collect([["item"=>"soap", "quantity"=>5],["item"=>"milk", "quantity"=>3],["item"=>"book", "quantity"=>5]])->fetchAllWithout("quantity", 5)->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #[["item"=>"milk", "quantity"=>3]]`
     *
     * @param $key
     * @param $value
     * @param $collection
     *
     * @return $this
     */
    public function fetchAllWithout($key, $value, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $newCollection = [];
        foreach ($this->useArgsOrPrevOutput($collection) as $index => $subCollection) {
            if (isset($subCollection[$key]) && $subCollection[$key] != $value) {
                $newCollection[] = $subCollection;
            }
        }
        $this->results = $newCollection;
        return $this;
    }

    /**
     *get a new collection of only a particular key value pair eg: `->beforeCreating()->collect([["item"=>"soap", "quantity"=>5],["item"=>"milk", "quantity"=>3],["item"=>"book", "quantity"=>5]])->fetchOnly("quantity")->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #[5,3,5]`
     *
     * @param $key
     * @param $value
     * @param $collection
     *
     * @return $this
     */
    public function fetchOnly($keys, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = array_column((array)$collection, $keys);
        $this->cleanOutput();
        return $this;
    }

    public function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
            $results[$key] = $value;
        }

        return $results;
    }
    /**
     *apply a method to a collection eg: `->beforeCreating()->collect(["Joe", "Mike"])->apply("convertToUpperCase", $params = [])->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["JOE","MIKE"]`
     *
     * @param $method
     * @param $params
     *
     * @return $this
     */
    public function apply($method, $params = [])
    {
        
        if (!$this->execOrNot) {
            return $this;
        }
        $parseParams = function ($params, $counter) {
            foreach ($params as $key => $value) {
                if (gettype($value) == 'array' && $value[0] = "#ITR") {
                        $data = $value[1];
                        $value[2] = str_replace('#counter', $counter, $value[2]);
                        $itrData = $this->dot($data)[$value[2]];
                        $params[$key] = $itrData;
                }
            }
            return $params;
        };
        

        $data = $this->results;
        $newOutput = [];
        if (is_scalar($data)) {
            return $this->$method(... $params);
        }
        foreach ($data as $key => $value) {
            $this->results = $value;
            $this->$method(... $parseParams($params, $key));
            $newOutput[] = $this->results;
        }
        $this->results = $newOutput;
        return $this;
    }


   
    public function onTheCollectionApplyMethod($method, $key = null, $newKey = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $params = func_get_args();
        unset($params[0], $params[1]);
        $input = $this->results;
        for ($i=0; $i < count($input); $i++) {
            if ($key) {
                $this->$method($input[$i][$key]);
            } else {
                $this->$method($input[$i]);
            }
            $mutatedValue = $this->results;
            if ($newKey) {
                $input[$i][$newKey] = $mutatedValue;
            } else {
                $input[$i][$key] = $mutatedValue;
            }
        }
        $this->results = $input;
        $this->cleanOutput();
        return $this;
    }

     /**
     *apply a method to a key in the collection eg: `->beforeCreating()->collect([["name"=>"Joe", "age"=>12],["name"=>"Mark", "age"=>23]])->applyOnElement("convertToUpperCase", "name" )->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #[["name"=>"JOE", "age"=>12],["name"=>"MARK", "age"=>23]]`
     *
     * @param $method
     * @param $params
     *
     * @return $this
     */
    public function applyOnElement($method, $key = null, $newKey = null)
    {
        $this->onTheCollectionApplyMethod($method, $key, $newKey);
        return $this;
    }

    public function forEachOneIn($items, $script, $variables = [])
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

    public function countElementsInCollection($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        if (is_string($this->results)) {
            $this->results = 0;
        } else {
            $this->results = count($this->useArgsOrPrevOutput($collection));
        }
        $this->cleanOutput();
        return $this;
    }

    /**
     *reverse the order of a collection eg: `->beforeCreating()->collect(["Joe", "Mike"])->reverseTheCollection()->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["Mike","Joe"]`
     *
     * @param $collection
     *
     * @return $this
     */
    public function reverseTheCollection($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = array_reverse($this->useArgsOrPrevOutput($collection));
        $this->cleanOutput();
        return $this;
    }

    /**
     *sort the order of a collection by a key eg: `->beforeCreating()->collect(["Zina", "Adam"])->sortCollectionBy("name")->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["Adam","Zina"]`
     *
     * @param $key
     * @param $collection
     *
     * @return $this
     */
    public function sortCollectionBy($key, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        sort($collection);
        $this->results = $collection;
        $this->cleanOutput();
        return $this;
    }

    /**
     * offsets N number of elements eg: `->beforeCreating()->collect(["Adam", "Ben", "Zina"])->offsetCollectionBy(1)->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["Ben","Zina"]`
     *
     * @param $offset
     * @param $collection
     *
     * @return $this
     */
    public function offsetCollectionBy($offset, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = array_splice($collection, $offset);
        $this->cleanOutput();
        return $this;
    }


    /**
     * reduce the number of elements to N eg: `->beforeCreating()->collect(["Adam", "Ben", "Zina"])->reduceNumberOfElementsTo(1)->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["Adam"]`
     *
     * @param $size
     * @param $collection
     *
     * @return $this
     */
    public function reduceNumberOfElementsTo($size, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = array_splice($collection, 0, $size);
        $this->cleanOutput();
        return $this;
    }

    /**
     * offset N elements and get Y elements eg: `->beforeCreating()->collect(["Adam", "Ben", "Zina"])->paginateCollection(1, 1)->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["Ben"]`
     *
     * @param $offset
     * @param $size
     * @param $collection
     *
     * @return $this
     */
    public function paginateCollection($offset, $size, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        $this->results = array_splice($collection, $offset, $size);
        $this->cleanOutput();
        return $this;
    }

    /**
     * find the difference between two collections eg: `->beforeCreating()->findCollectionDiffs(["name"=>"Mark", "age"=>45], ["name"=>"Mark"])->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #  ["age"=> 45]`
     *
     * @param $collectionOne
     * @param $collectionTwo
     *
     * @return $this
     */
    public function findCollectionDiffs($collectionOne, $collectionTwo)
    {
        
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = collect($collectionOne)->diff(collect($collectionTwo));
        $this->cleanOutput();
        return $this;
    }

    /**
     * expand and flatten a collection eg: `->beforeCreating()->expandCollection(["name"=>["Mark", "zowy"], "age"=>45])->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #[["name"=>"Mark","age"=>45],["name"=>"zowy","age"=>45]]`
     *
     * @param $collection

     *
     * @return $this
     */
    public function expandCollection($collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $output = [];
        $template = [];
    
        foreach ($this->useArgsOrPrevOutput($collection) as $key => $data) {
            $push_to_array  = function () use (&$output, &$eachValue, &$template, &$key) {
                $innerTemplate = $template;
                $innerTemplate[$key] = $eachValue;
                $output[] = $innerTemplate;
            };
            if (!is_scalar($data)) {
                foreach ($data as $index => $eachValue) {
                    (isset($output[$index]))?
                    $output[$index][$key] = $eachValue :
                    $push_to_array();
                }
            } else {
                for ($i=0; $i< count($output); $i++) {
                    $output[$i][$key] = $data;
                }
            }
        }
        $this->results = $output;
        $this->cleanOutput();
        return $this;
    }

     /**
     * add an element to a collection eg: `->beforeCreating()->collect(["name"=>"mike"])->addElementToCollection(23,"age")->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["name"=>"mike","age"=>23]`
     *
     * @param $element
     * @param $key
     * @return $this
     */
    public function addElementToCollection($element, $key = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }

        $this->results = collect($this->results)->prepend($element, $key);
        $this->cleanOutput();
        return $this;
    }

    /**
     * remove an element from collection  eg: `->beforeCreating()->collect(["age"=>23,"name"=>"mike"])->removeELementFromCollection("age")->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["name"=>"mike","age"=>23] #["name"=>"mike"]`
     *
     * @param $elementKey
     * @param $collection
     * @return $this
     */
    public function removeElementFromCollection($elementKey, $collection = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $collection = $this->useArgsOrPrevOutput($collection);
        unset($collection[$elementKey]);
        $this->results = $collection;
        $this->cleanOutput();
        return $this;
    }

    /**
     * create key value pairs from two collections  eg: `->beforeCreating()->collect(["Mark",23])->useCollectionAsKeys(["name", "age"])->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #["name"=>"Mark","age"=>23]`
     *
     * @param $collection
     * @return $this
     */
    public function useCollectionAsKeys($collection)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = collect(array_combine($collection, $this->results));
        $this->cleanOutput();
        return $this;
    }

    /**
     * check if a collection contains a key or value  eg: `->beforeCreating()->collect(["Mark",23])->checkIfCollectionContains(["Mark"])->storeAs($collection)->stopAndOutput(1000, "got response", $collection) #true`
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function checkIfCollectionContains($key, $value = null)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        $this->results = collect($this->results)->contains($key, $value);
        $this->cleanOutput();
        return $this;
    }

    public function isAssoc(array $arr)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    
    public function objToArray($obj, &$arr)
    {
        if (!$this->execOrNot) {
            return $this;
        }
        if (!is_object($obj) && !is_array($obj)) {
            $arr = $obj;
            return $arr;
        }
        foreach ($obj as $key => $value) {
            if (!empty($value)) {
                $arr[$key] = array();
                $this->objToArray($value, $arr[$key]);
            } else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
}
