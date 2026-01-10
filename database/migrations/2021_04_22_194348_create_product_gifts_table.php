<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductGiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_gift', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();

            $table->integer('gift_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products')

                ->onDelete('cascade');

            $table->foreign('gift_id')->references('id')->on('gifts')

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
        Schema::dropIfExists('product_gift');
    }
}
