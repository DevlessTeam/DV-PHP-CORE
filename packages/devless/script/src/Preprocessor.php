<?php

namespace Devless\Script;

use PhpParser\Node;
use App\Helpers\Helper;
use App\Helpers\DevlessHelper as DLH;
use Devless\RulesEngine\Rules;
use PhpParser\NodeVisitorAbstract;

trait preprocessor 
{
	private function convertConstToVars($node) 
	{
		if( isset($node->name, $node->args) ) {
			for($i=0; $i< count($node->args); $i++) {
				if($node->args[$i]->value instanceof Node\Expr\ConstFetch){
					$nodeName = $node->args[$i]->value->name->parts[0];
					$newVariable = (new Node\Expr\Variable($nodeName, ["startLine" => 200, "endLine" => 200]));
					$node->args[$i]->value = $newVariable;
				}
			}
		}
		return $node;
	}

	private function checkIfRuleExists($node) {
		
		if( $node instanceof Node\Expr\MethodCall && isset($node->name)) {
			$methodName = $node->name;
			$ruleMethods = get_class_methods(new Rules());
			if(! in_array($methodName, $ruleMethods)){
				$closestWord = DLH::find_closest_word($methodName, $ruleMethods);
				$message = ( strlen($closestWord)> 0 )?"The method `$methodName` does not exist maybe you meant `".$closestWord. "` ?" :"The method `$methodName` does not exist";
				Helper::interrupt(1001, $message);
			}
		}
		return $node;
	}
}