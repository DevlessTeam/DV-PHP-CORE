<?php

use Devless\Schema\DbHandler;

class SystemClassTest extends TestCase
{

    private $email = "testit@test.com";
    private $password = "password";
    private $username = "testit";

    public function setUp()
    {
        parent::setUp();
        $dbHandler = new DbHandler();
        $payload = $this->getPayload();
        $dbHandler->create_schema($payload);
    }

    /**
     * @test
     * signup user.
     */
    public function it_should_signup_a_user()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $user = $devless->signUp(
            $this->email,
            $this->password,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        $this->assertNotEmpty($user);
        $this->assertArrayHasKey('profile', $user);
        $this->assertArrayHasKey('token', $user);
        $this->assertNotEmpty($user['profile']);
        $this->assertTrue(strlen($user['token']) > 0);
        $this->assertEquals('object', gettype($user['profile']));
    }


     /**
     * @test
     * @depends it_should_signup_a_user
     * get the list of all users.
     */
    public function it_should_get_all_users()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $users = $devless->getAllUsers();
        $this->assertEquals("array", gettype($users));
        $this->assertEquals(1, count($users));
    }


     /**
     * @test
     * it should log user in .
     */
    public function it_should_log_user_in()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $user = $devless->login(null, $this->adminEmail, null, $this->adminPassword);
        $this->assertNotEmpty($user);
        $this->assertArrayHasKey('profile', $user);
        $this->assertArrayHasKey('token', $user);
        $this->assertNotEmpty($user['profile']);
        $this->assertTrue(strlen($user['token']) > 0);
        $this->assertEquals('array', gettype($user['profile']));
    }


     /**
     * @test
     * it should get user profile  .
     */
    public function it_should_get_user_profile()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $user = $devless->profile();
        $this->assertNotEmpty($user);
        $this->assertEquals('array', gettype($user));
        $this->assertEquals(11, count($user));
    }


     /**
     * @test
     * it should update user profile.
     */
    public function it_should_update_profile()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $user = $devless->updateProfile(
            'test2@mail.com',
            null,
            'kofi',
            '0000000000',
            'james',
            'rich',
            null,
            null
        );

        $this->assertNotEmpty($user);
        $this->assertEquals('array', gettype($user));
        $this->assertEquals(7, count($user));
        $this->assertArrayHasKey('id', $user);
        $this->assertEquals('kofi', $user['username']);
        $this->assertEquals('rich', $user['last_name']);
        $this->assertEquals('0000000000', $user['phone_number']);
        $this->assertEquals('test2@mail.com', $user['email']);
    }

     /**
     * @test
     * it should update user profile using private method.
     */
    public function it_should_update_user_profile()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $user = $devless->updateUserProfile(
            1,
            'testt@mail.com',
            '',
            'aba',
            '0000000003',
            'go',
            'rch',
            '',
            ''
        );

        $this->assertNotEmpty($user);
        $this->assertTrue($user);
    }
   

     /**
     * @test
     * it should login user using username.
     */
    public function it_should_login_user_with_username()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $user = $devless->usernameLogin('Add username here', 'password');
        $this->assertNotEmpty($user);
        $this->assertArrayHasKey('profile', $user);
        $this->assertArrayHasKey('token', $user);
        $this->assertNotEmpty($user['profile']);
        $this->assertTrue(strlen($user['token']) > 0);
        $this->assertEquals('array', gettype($user['profile']));
    }

    /**
     * @test
     * it should login users with email.
     */
    public function it_should_login_user_with_email()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
         $user = $devless->updateUserProfile(
             1,
             'testt@mail.com',
             '',
             'aba',
             '0000000003',
             'go',
             'rch',
             '',
             ''
         );
        $user = $devless->usernameLogin('aba', 'password');
        $user = $devless->phoneNumberLogin('0000000003', 'password');
        $this->assertNotEmpty($user);
        $this->assertArrayHasKey('profile', $user);
        $this->assertArrayHasKey('token', $user);
        $this->assertNotEmpty($user['profile']);
        $this->assertTrue(strlen($user['token']) > 0);
        $this->assertEquals('array', gettype($user['profile']));
    }

    /**
    * @test
    * it should add data to a service.
    */
    public function it_should_add_service_data()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $output = $devless->addData('testservice', 'serviceTable', ["username"=>"edmond", "password"=>"password"]);
        $this->assertNotEmpty($output);
        $this->assertArrayHasKey('status_code', $output);
        $this->assertArrayHasKey('message', $output);
        $this->assertArrayHasKey('payload', $output);
        $this->assertNotEmpty($output['payload']['entry_id']);
        $this->assertEquals(609, $output['status_code']);
    }


    /**
    * @test
    * it should get data from a service.
    */
    public function it_should_get_service_data()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $devless->addData('testservice', 'serviceTable', ["username"=>"edmond", "password"=>"password"]);
        $output = $devless->getData('testservice', 'serviceTable');
        $this->assertNotEmpty($output);
        $this->assertEquals('edmond', $output[0]->username);
        $this->assertEquals(1, $output[0]->devless_user_id);
    }

    /**
    * @test
    * it should update data from a service.
    */
    public function it_should_update_service_data()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $devless->addData('testservice', 'serviceTable', ["username"=>"edmond", "password"=>"password"]);
        $output = $devless->updateData('testservice', 'serviceTable', 'id', 1, ["username"=>"eddy"]);

        $this->assertNotEmpty($output);
        $this->assertArrayHasKey('status_code', $output);
        $this->assertArrayHasKey('message', $output);
        $this->assertArrayHasKey('payload', $output);
        $this->assertEquals(619, $output['status_code']);
    }

    /**
    * @test
    * it should delete data from a service.
    */
    public function it_should_delete_service_data()
    {
        require_once config('devless')['system_class'];
        $devless = new devless();
        $devless->addData('testservice', 'serviceTable', ["username"=>"edmond", "password"=>"password"]);
        $output = $devless->deleteData('testservice', 'serviceTable', 1);

        $this->assertNotEmpty($output);
        $this->assertArrayHasKey('status_code', $output);
        $this->assertArrayHasKey('message', $output);
        $this->assertArrayHasKey('payload', $output);
        $this->assertEquals(636, $output['status_code']);
    }
    private function getPayload()
    {
        return
        [
        "id" => "2",
        "service_name" => "testservice",
        "database" => "",
        "driver" => "default",
        "hostname" => "",
        "username" => "",
        "password" => "",
        "resource_access_right" =>  [
        "query" => 1,
        "update" => 1,
        "delete" => 1,
        "script" => 1,
        "schema" => 1,
        "create" => 1,
        "view" => 1,
        ],
        "script" => "->beforeQuerying()->beforeUpdating()->beforeDeleting()->beforeCreating()->onQuery()->onUpdate()->onDelete()->onCreate()->onAnyRequest()->afterQuerying()->afterUpdating()->afterDeleting()->afterCreating();",
        "port" => "",
        "method" => "POST",
        "params" => [
         [
          "name" => "serviceTable",
          "description" => "demo table",
          "field" => [
             [
              "name" => "username",
              "field_type" => "text",
              "default" => null,
              "required" => true,
              "is_unique" => true,
              "ref_table" => "",
              "validation" => true,
             ],
             [
              "name" => "password",
              "field_type" => "password",
              "default" => null,
              "required" => true,
              "is_unique" => false,
              "ref_table" => "",
              "validation" => true,
             ]
          ]
         ]
        ],
        "request_phase" => "before",
        ];
    }
}
