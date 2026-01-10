<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approves', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('number')->nullable()->unsigned();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::table('bookings', function (Blueprint $table){
            $table->integer('approve_id')->unsigned()->nullable()->default(1);

            $table->foreign('approve_id')
                ->references('id')
                ->on('approves');
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
            $table->dropForeign(['approve_id']);
            $table->dropColumn("approve_id");
        });
        Schema::dropIfExists('approves');
    }
}
