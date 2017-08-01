<?php

namespace Devless\Script;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class Transverser extends NodeVisitorAbstract
{
	use preprocessor;

	public function enterNode( Node $node) {
		$node = $this->checkIfRuleExists($node);
		$node = $this->convertConstToVars($node);
		return $node;
	}
}