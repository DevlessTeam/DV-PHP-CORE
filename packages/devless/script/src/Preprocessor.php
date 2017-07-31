<?php

namespace Devless\Script;

use PhpParser\Node;
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
}