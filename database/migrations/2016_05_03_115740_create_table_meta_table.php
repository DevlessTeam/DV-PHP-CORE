<?php

use Illuminate\Database\Migrations\Migration;

class CreateTableMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('table_metas', function ($table) {
            $table->increments('id');
            $table->string('table_name');
            $table->json('schema');
            $table->integer('count')->nullable();
            $table->boolean('access')->nullable();
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('services')
                   ->onDelete('cascade');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('table_metas');
    }
}
