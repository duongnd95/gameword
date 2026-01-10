<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->integer('apply_target')->default(1);
            $table->integer('type')->default(0);
            $table->boolean('invoice_status')->default(0);
            $table->double('invoice_total')->nullable();
            $table->double('percent_off')->nullable();
            $table->double('max_sale')->nullable();
            $table->double('sale_price')->nullable();
            $table->integer('active')->nullable()->default(0);
            $table->date('expires_at')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
