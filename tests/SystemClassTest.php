<?php

class SystemClassTest extends TestCase
{

    private $email = "testit@test.com";
    private $password = "password";
    private $username = "testit";

   
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
            null,null,null,null,null,
            null,null);
        $this->assertNotEmpty($user);
        $this->assertArrayHasKey('profile', $user);
        $this->assertArrayHasKey('token', $user);
        $this->assertNotEmpty($user['profile']);
        $this->assertTrue( strlen($user['token']) > 0 );
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
        $user = $devless->login(null, $this->adminEmail,null, $this->adminPassword);
        $this->assertNotEmpty($user);
        $this->assertArrayHasKey('profile', $user);
        $this->assertArrayHasKey('token', $user);
        $this->assertNotEmpty($user['profile']);
        $this->assertTrue( strlen($user['token']) > 0 );
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
        'test2@mail.com',null,'kofi','0000000000','james','rich',null,null);

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
    // public function it_should_update_user_profile()
    // {
    //     require_once config('devless')['system_class'];
    //     $devless = new devless();
    //     $user = $devless->updateUserProfile(1,
    //     'testt@mail.com','','aba','0000000003','go','rch','','');

    //     $this->assertNotEmpty($user);
    //     $this->assertEquals('array', gettype($user));
    //     $this->assertEquals(7, count($user));
    //     $this->assertArrayHasKey('id', $user);
    //     $this->assertEquals('kofi', $user['username']);
    //     $this->assertEquals('rich', $user['last_name']);
    //     $this->assertEquals('0000000000', $user['phone_number']);
    //     $this->assertEquals('test2@mail.com', $user['email']);
    // }
   
}
