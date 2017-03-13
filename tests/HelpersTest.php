<?php

use App\Helpers\Helper;
use App\User;
use Illuminate\Support\Facades\Auth;

class HelpersTest extends TestCase
{
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        Auth::login($this->user);
    }

    /**
     * @test
     * Message stack test.
     *
     * @return void
     */
    public function it_should_check_response_message()
    {

        $stack = rand(0, 614);

        $outputType = gettype(Helper::responseMessage($stack));

        $assetAgainst = ($stack > 600)? 'string' : 'NULL';

        $this->assertEquals($assetAgainst, $outputType);


    }


    /**
     * @test
     * Field validator test.
     *
     * @return void
     */
    public function it_should_validate_fields()
    {
        $validSample =
            [
                'boolean'    => true,
                'decimals'   => 12.2324344,
                'email'      => 'edmond@devless.io',
                'integer'    => 1,
                'password'   => 'al32j4hk2jh4hghg23hghj',
                'percentage' => 1,
                'reference'  => 1,
                'text'       => 'string',
                'textarea'   => 'string',
                'timestamp'  => 12345,
                'url'        => 'https://devless.io/#!/main',
                'base64'     => 'abcd1234',

            ];

        $invalidSample =
            [
                'boolean'    => 'true',
                'decimals'   => 'string instead of decimal',
                'email'      => 'edmonddevless.io',
                'integer'    => 'string here',
                'password'   => true,
                'percentage' => 12.4,
                'reference'  => 'ref',
                'text'       => 1,
                'textarea'   => 2,
                'timestamp'  => 'timestamp',
                'url'        => 'devless.io/#!/main',
                'base64'     => 'random string',

            ];

        $fieldTypes  = Helper::$validator_type;

        foreach ($fieldTypes as $fieldType => $vaidatorKey) {
            //check against valid field types
            $output = Helper::field_check($validSample[$fieldType], $fieldType);
            $this->assertTrue($output);

            //check against wrong field types
            $output = Helper::field_check($invalidSample[$fieldType], $fieldType);
            $this->assertFalse($output);
        }
    }


    /**
     * @test
     * url query string test.
     *
     * @return void
     */
    public function it_should_check_query_string()
    {
        $_SERVER['QUERY_STRING'] = 'name=edmond&name=charles&age=12';

        $output = Helper::query_string();
        $this->assertEquals($output['name'][0], 'edmond');
        $this->assertEquals($output['name'][1], 'charles');
        $this->assertEquals($output['age'][0], '12');

        //query string when parameters are not set
        unset($_SERVER['QUERY_STRING']);
        $output = Helper::query_string();
        $this->assertEquals('', $output);
    }


    /**
     * @test
     * sessionTimestamp test.
     *
     * @return void
     */
    public function it_should_check_session_time_stamp()
    {
        $sessionTime = Helper::session_timestamp();

        $formattedSessionTime = date('Y-m-d', strtotime($sessionTime));

        $this->assertEquals(date('Y-m-d'), $formattedSessionTime);
    }
}
