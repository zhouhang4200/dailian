<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameLevelingOrderComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_leveling_order_complains', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('game_leveling_orders_trade_no', 22)->comment('代练订单交易号');
            $table->decimal('amount', 17, 2)->default(0)->comment('代练费');
            $table->decimal('security_deposit', 17, 2)->default(0)->comment('安全保证金');
            $table->decimal('efficiency_deposit', 17, 2)->default(0)->comment('效率保证金');
            $table->string('remark', 500)->comment('备注');
            $table->string('result', 500)->comment('处理结果');
            $table->unsignedTinyInteger('status')->comment('状态 1 处理中 2 成功');
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
        Schema::dropIfExists('game_leveling_order_complains');
    }
}
