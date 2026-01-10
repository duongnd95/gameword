<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_other')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('address')->nullable();
	        $table->string('permanent_address')->nullable();
	        $table->string('facebook')->nullable();
	        $table->string('zalo')->nullable();
	        $table->integer('creator_id')->unsigned()->nullable();
            $table->timestamps();

	        $table->foreign('creator_id')
	              ->references('id')
	              ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customers');
    }
}
