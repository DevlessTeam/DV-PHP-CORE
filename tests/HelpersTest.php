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


    /**
     * Field validator test.
     *
     * @return void
     */
    public function testFieldCheck()
    {
        $validSample =
            [
                'boolean'    => true,
                'decimals'    => 12.2324344,
                'email'      => 'edmond@devless.io',
                'integer'    => 1,
                'password'   => 'al32j4hk2jh4hghg23hghj',
                'percentage' => 1,
                'reference'  => 1,
                'text'       => 'string',
                'textarea'   => 'string',
                'timestamp'  => 12345,
                'url'        => 'https://devless.io/#!/main',

            ];

        $invalidSample =
            [
                'boolean'    => 'true',
                'decimals'    => 'string instead of decimal',
                'email'      => 'edmonddevless.io',
                'integer'    => 'string here',
                'password'   => true,
                'percentage' => 12.4,
                'reference'  => 'ref',
                'text'       => 1,
                'textarea'   => 2,
                'timestamp'  => 'timestamp',
                'url'        => 'devless.io/#!/main',

            ];

        $fieldTypes  = Helper::$validator_type;

        foreach($fieldTypes as $fieldType => $vaidatorKey){

            //check against valid field types
            $output = Helper::field_check($validSample[$fieldType], $fieldType);
            $this->assertTrue($output);

            //check against wrong field types
            $output = Helper::field_check($invalidSample[$fieldType], $fieldType);
            $type   = gettype($output);
            $this->assertEquals('object', $type);

        }

    }


    /**
     * url query string test.
     *
     * @return void
     */
    public function testQueryString()
    {
        $_SERVER['QUERY_STRING'] = 'name=edmond&name=charles&age=12';

        $output = Helper::query_string();
        $this->assertEquals($output['name'][0],'edmond');
        $this->assertEquals($output['name'][1],'charles');
        $this->assertEquals($output['age'][0],'12');

        //query string when parameters are not set
        unset($_SERVER['QUERY_STRING']);
        $output = Helper::query_string();
        $this->assertEquals('', $output);


    }


    /**
     * sessionTimestamp test.
     *
     * @return void
     */
    public function testSessionTimestamp()
    {
        $sessionTime = Helper::session_timestamp();

        $formattedSessionTime = date('Y-m-d',strtotime($sessionTime));

        $this->assertEquals(date('Y-m-d'), $formattedSessionTime);


    }


}





