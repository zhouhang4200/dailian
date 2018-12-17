<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSpreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_spreads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('被推广人ID（推广返利收入人ID）');
            $table->float('spread_rate', 3, 2)->comment('推广返利比例，如0.01,0.05');
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
        Schema::dropIfExists('user_spreads');
    }
}
