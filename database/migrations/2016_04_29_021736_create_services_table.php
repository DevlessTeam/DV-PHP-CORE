<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('services', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('driver')->nullable();
            $table->text('hostname')->nullable();
            $table->text('username')->nullable();
            $table->text('password')->nullable();
            $table->text('database')->nullable();
            $table->text('script');
            $table->text('resource_access_right');
                 // ->default('{"query":1,"create":2,"read":0,"update":1,"delete":1,"schema":0}');
            $table->boolean('public')->nullable();
            $table->boolean('active');
            $table->integer('calls')->nullable();
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
		Schema::drop('services');
	}

}
