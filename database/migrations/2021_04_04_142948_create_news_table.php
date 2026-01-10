<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('active')->unsigned()->nullable()->default(1);
            $table->tinyInteger('is_focus')->unsigned()->nullable()->default(0);
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('editor_id')->unsigned()->nullable();
            $table->foreign('creator_id')
                ->references('id')
                ->on('users');
            $table->foreign('editor_id')
                ->references('id')
                ->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
