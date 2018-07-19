<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameLevelingOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_leveling_order_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('username', 60)->comment('用户名称');
            $table->string('name', 60)->comment('操作名称');
            $table->string('description', 600)->comment('操作详情');
            $table->unsignedInteger('parent_user_id')->comment('用户父ID');
            $table->string('game_leveling_orders_trade_no', 22)->comment('代练订单交易号');
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
        Schema::dropIfExists('game_leveling_order_logs');
    }
}
