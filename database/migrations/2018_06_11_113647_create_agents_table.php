<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->date('birthday')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
	    Schema::table('users', function (Blueprint $table) {
		    $table->integer('agent_id')->unsigned()->nullable();
		    $table->foreign('agent_id')
		          ->references('id')
		          ->on('agents')
		          ->onDelete('cascade');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('users', function (Blueprint $table) {
		    $table->dropForeign(['agent_id']);
		    $table->dropColumn("agent_id");
	    });
        Schema::drop('agents');
    }
}
