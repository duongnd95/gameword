<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('code')->nullable();
	        $table->double('total_price');
	        $table->mediumText('note')->nullable();
	        $table->mediumText('cancel_note')->nullable();
	        $table->integer('creator_id')->unsigned()->nullable();
	        $table->integer('customer_id')->unsigned()->nullable();

	        $table->softDeletes();
	        $table->timestamps();

	        $table->foreign('creator_id')
	              ->references('id')
	              ->on('users');
	        $table->foreign('customer_id')
	              ->references('id')
	              ->on('customers');
        });
	    Schema::create('booking_detail', function (Blueprint $table) {
		    $table->double('price');
            $table->tinyInteger('quantity')->default(0);
		    $table->integer('booking_id')->unsigned();
		    $table->morphs('bookingable');

		    $table->foreign('booking_id')
		          ->references('id')
		          ->on('bookings')
		          ->onDelete('cascade');
	    });
	    Schema::create('booking_properties', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('key');
		    $table->string('name');
		    $table->string('type');
		    $table->text('data');
		    $table->string('module');
	    });
	    Schema::create('booking_property_values', function (Blueprint $table) {
		    $table->string('value');
		    $table->integer('booking_property_id')->unsigned();
		    $table->integer('booking_id')->unsigned();

		    $table->foreign('booking_property_id')
		          ->references('id')
		          ->on('booking_properties')
		          ->onDelete('cascade');
		    $table->foreign('booking_id')
		          ->references('id')
		          ->on('bookings')
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
	    Schema::drop('booking_property_values');
	    Schema::drop('booking_properties');
	    Schema::drop('booking_detail');
        Schema::drop('bookings');
    }
}
