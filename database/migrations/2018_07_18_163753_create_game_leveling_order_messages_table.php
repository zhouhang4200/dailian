<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameLevelingOrderMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_leveling_order_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_leveling_order_trade_no')->comment('交易单号');
            $table->unsignedInteger('initiator')->comment('留言发送人 1 发单方 2 接单方 3 平台工作人员');
            $table->unsignedInteger('from_user_id')->comment('留言发送人ID');
            $table->string('from_username')->comment('留言发送人名字');
            $table->unsignedInteger('to_user_id')->comment('留言接收人ID');
            $table->string('to_username')->comment('留言接收人名字');
            $table->string('content')->comment('内容');
            $table->string('type')->comment('1 仲裁留言 2 普通订单留言');
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
        Schema::dropIfExists('game_leveling_order_messages');
    }
}
