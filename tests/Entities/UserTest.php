<?php

use App\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $users;

    /**
     * Test setup
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:reset');
        $this->artisan('migrate');

        $this->users = factory(User::class, 10)->create();
    }

    /**
     * @test
     */
    public function it_should_return_the_count_of_users_created()
    {
        $users = User::all();
        
        $this->assertCount(10, $users);
    }

    /**
     * @test
     */
    public function it_should_return_the_casted_role_and_status()
    {
        $user = factory(User::class)->create(['role' => 0, 'status' => 1]);

        $this->assertEquals($user->role, false);
        $this->assertEquals($user->status, true);
        $this->assertEquals($this->users->first()->role, true);
        $this->assertEquals($this->users->first()->status, false);
    }

    /**
     * @test
     */

    public function it_should_hash_password()
    {
        $user = factory(User::class)->create(['password' => 'userpassword']);

        $this->assertTrue(Hash::check('userpassword', $user->password));
    }

    /**
     * @test
     */
    public function it_checks_admin_role()
    {
        $user = factory(User::class)->create(['role' => 0]);

        $this->assertTrue($this->users->first()->isAdmin());
        $this->assertFalse($user->isAdmin());
    }
}
