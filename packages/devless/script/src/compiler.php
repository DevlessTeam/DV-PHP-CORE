<?php
namespace Devless\Script;

use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

trait compiler
{
   
    public $firstly = null;

    public function compile_script($code)
    {
        //check for syntax, methods and also if attributes exists
        $code = ('<?php $rules'.$code.';');
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;
        $traverser->addVisitor(new Transverser);
        $prettyPrinter = new PrettyPrinter\Standard;
        try {
            $stmts = $parser->parse($code);
            $stmts = $traverser->traverse($stmts);
            $code = substr($prettyPrinter->prettyPrint($stmts), 6);
        } catch (Error $e) {
            $compiled_script['error_message'] = $e->getMessage();
            $compiled_script['successful'] = false;
            return $compiled_script;
        }
        
        $declarationString = '';
        $tokens = token_get_all('<?php '.$code);
        
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $start = 1;
                if ($token[0] == 312) {
                    $variable = substr($token[1], $start);
                    $declarationString .= "$$variable = null;";
                }
            }
        };
        $compiled_script['var_init'] = $declarationString;
        $compiled_script['script'] = $code;
        $compiled_script['successful'] = true;
        return $compiled_script;
    }
}
