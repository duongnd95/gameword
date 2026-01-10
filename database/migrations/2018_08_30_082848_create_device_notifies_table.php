<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_notifies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token')->unique();
	        $table->string('device_type')->nullable();
	        $table->string('device_info')->nullable();
	        $table->integer('user_id')->unsigned();
            $table->timestamps();

	        $table->foreign('user_id')
	              ->references('id')
	              ->on('users')
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
        Schema::dropIfExists('device_notifies');
    }
}
