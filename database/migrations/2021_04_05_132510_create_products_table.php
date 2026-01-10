<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');  
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->double('price');
            $table->double('price_compare')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('delivery')->default(0);
            $table->boolean('inventory')->default(0);
            $table->string('amount_inventory')->nullable()->default(0);
            $table->tinyInteger('active')->unsigned()->nullable()->default(1);
            $table->tinyInteger('arrange')->unsigned()->nullable()->default(1);
            $table->tinyInteger('new')->unsigned()->nullable()->default(0);
            $table->tinyInteger('hot')->unsigned()->nullable()->default(0);
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('provider_id')->nullable();
            $table->string('model')->nullable();
            $table->string('preservation')->nullable();
            $table->string('sku')->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('editor_id')->unsigned()->nullable();
            $table->foreign('creator_id')
                ->references('id')
                ->on('users');
            $table->foreign('category_id')->references('id')->on('category_products')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('provider_products')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
