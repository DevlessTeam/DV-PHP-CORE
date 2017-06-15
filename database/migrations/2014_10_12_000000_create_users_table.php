<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->default('NA');
            $table->string('email')->nullable();
            $table->string('password', 60);
            $table->boolean('role')->default(0);
            $table->string('first_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('status')->default(1);
            $table->string('session_token')->nullable();
            $table->string('session_time')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
