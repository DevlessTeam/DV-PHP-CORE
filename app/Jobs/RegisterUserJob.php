<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Http\Request;
use App\User;
use App\App;
use App\Jobs\AddAppJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RegisterUserJob extends Job
{
    use DispatchesJobs;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param null    $user
     */
    public function __construct(Request $request, $user = null)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->register();
    }

    private function register()
    {
        if (empty($this->user)) {
            $this->user = new User();
            $this->user->username = $this->request->input('username');
            $this->user->email = $this->request->input('email');
            $this->user->password = $this->request->input('password');
            $this->user->role = 1;
            $this->user->status = 1;
        }

        // Create app
        $this->dispatch(
            new AddAppJob(
                (object)[
                'name' => $this->request->input('app_name'),
                'description' => $this->request->input('app_description'),
                'token' => App::get_token()
                ]
            )
        );

        $this->user->save();

        return $this->user;
    }
}
