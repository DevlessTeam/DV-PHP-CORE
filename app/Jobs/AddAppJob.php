<?php

namespace App\Jobs;

use App\App;
use Illuminate\Contracts\Bus\SelfHandling;

class AddAppJob extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->createApp();
    }

    /**
     * Create App
     *
     * @return static
     */
    private function createApp()
    {
        return App::create([
            'name' => $this->details->name,
            'description' => $this->details->description,
            'token' => $this->details->token
        ]);
    }
}