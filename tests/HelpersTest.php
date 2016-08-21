<?php

use App\Helpers\Helper;

class HelpersTest extends PHPUnit_Framework_TestCase
{
    /**
     * Message stack test.
     *
     * @return void
     */
    public function testResponseMessage()
    {

        $stack = rand(0, 614);
        $outputType = gettype(Helper::responseMessage($stack));
        $assetAgainst = ($stack > 600)? 'string' : 'NULL';
        $this->assertEquals($assetAgainst, $outputType);


    }
}
