<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPoundagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_poundages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户主id');
            $table->float('send_poundage', 3, 2)->default(0)->comment('发单手续费');
            $table->float('take_poundage', 3, 2)->default(0)->comment('接单手续费');
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
        Schema::dropIfExists('user_poundages');
    }
}
