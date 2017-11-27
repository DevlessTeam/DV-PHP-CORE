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
        if (isset($node->name, $node->args)) {
            for ($i=0; $i< count($node->args); $i++) {
                if ($node->args[$i]->value instanceof Node\Expr\ConstFetch) {
                    $nodeName = $node->args[$i]->value->name->parts[0];
                    $newVariable = (new Node\Expr\Variable($nodeName, ["startLine" => 200, "endLine" => 200]));
                    $node->args[$i]->value = $newVariable;
                } elseif ($node->args[$i]->value instanceof Node\Expr\Array_) {
                    $numOFArgs = count($node->args[$i]->value->items);
                    for ($j=0; $j< $numOFArgs; $j++) {
                        if ($node->args[$i]->value->items[$j]->key instanceof Node\Expr\ConstFetch) {
                            $keyName = $node->args[$i]->value->items[$j]->key->name->parts[0];
                            $node->args[$i]->value->items[$j]->key = (new Node\Expr\Variable($keyName, ["startLine" => 200, "endLine" => 200]));
                        }
                        if ($node->args[$i]->value->items[$j]->value instanceof Node\Expr\ConstFetch) {
                            $valueName = $node->args[$i]->value->items[$j]->value->name->parts[0];
                            $node->args[$i]->value->items[$j]->value = (new Node\Expr\Variable($valueName, ["startLine" => 200, "endLine" => 200]));
                        }
                    }
                } elseif ($node->args[$i]->value instanceof Node\Expr\ArrayDimFetch  && $node->args[$i]->value->var instanceof Node\Expr\ConstFetch) {
                    $nodeName = $node->args[$i]->value->var->name->parts[0];
                    $dim      = $node->args[$i]->value->dim;
                    $newVariable = (new Node\Expr\Variable($nodeName, ["startLine" => 200, "endLine" => 200]));
                    $newArrayVariable = (new Node\Expr\ArrayDimFetch($newVariable, $dim, ["startLine" => 200, "endLine" => 200]));
                    $node->args[$i]->value = $newArrayVariable;
                }
            }
        }
        return $node;
    }

    private function checkIfRuleExists($node)
    {
        
        // if ($node instanceof Node\Expr\MethodCall && isset($node->name)) {
        //     $methodName = $node->name;
        //     $ruleMethods = get_class_methods(new Rules());
        //     if (! in_array($methodName, $ruleMethods)) {
        //         $closestWord = DLH::find_closest_word($methodName, $ruleMethods);
        //         $message = ( strlen($closestWord)> 0 )?"The method `$methodName` does not exist maybe you meant `".$closestWord. "` ?" :"The method `$methodName` does not exist";
        //         Helper::interrupt(1001, $message);
        //     }
        // }
        return $node;
    }
}
