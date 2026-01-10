<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('active')->unsigned()->nullable()->default(1);
            $table->timestamps();
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('payment_id')->unsigned()->nullable();
            $table->foreign('payment_id')
                ->references('id')
                ->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_id');
            $table->dropForeign('payment_id');
        });
        Schema::dropIfExists('payment_methods');
    }
}
