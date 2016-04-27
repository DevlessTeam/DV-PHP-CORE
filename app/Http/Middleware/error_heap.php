<?php

#error heap
class ErrorHeap{
    
    public $ERROR_HEAP = [
        800 => 'Sorry something went wrong with payload(check json format)',
        801 => 'Underflow or the modes mismatch',
        802 => 'Unexpected control character found',
        803 => 'Syntax error, malformed JSON',
        804 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        805 => 'Unknown error',
        ];
    
         /**
          * fetch message based on error code 
         * @param  stack  $stack
         * @return string or null  
         **/
        
    public function find_stack($stack){
        if(isset($this->ERROR_HEAP[$stack]))
            return $this->ERROR_HEAP[$stack];
        else
            {
              return null;
            }
    }

   } 