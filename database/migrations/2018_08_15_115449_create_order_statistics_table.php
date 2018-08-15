<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->comment('发单日期');
            $table->string('trade_no')->comment('订单号');
            $table->tinyInteger('status')->comment('订单状态');
            $table->integer('game_id')->comment('游戏id');
            $table->string('game_name')->comment('游戏名称');
            $table->integer('consult_creator')->default(0)->comment('撤销发起人: 默认0 没有发起撤销, 1 发单发起， 2 接单发起');
            $table->integer('complain_creator')->default(0)->comment('仲裁发起人: 默认0 没有发起撤销, 1 发单发起， 2 接单发起');
            $table->integer('user_id')->unsigned()->comment('发单人id');
            $table->integer('parent_user_id')->unsigned()->comment('发单人主id');
            $table->integer('take_user_id')->unsigned()->default(0)->comment('接单人id, 默认0 无人接单');
            $table->integer('take_parent_user_id')->unsigned()->default(0)->comment('接单人主id， 默认0 无人接单');
            $table->decimal('amount', 10, 2)->comment('发单金额');
            $table->decimal('security_deposit', 10, 2)->comment('安全保证金');
            $table->decimal('efficiency_deposit', 10, 2)->comment('效率保证金');
            $table->decimal('consult_complain_amount', 10, 2)->default(0)->comment('撤销/仲裁发单方支出的代练费');
            $table->decimal('consult_complain_deposit', 10, 2)->default(0)->comment('撤销/仲裁接单方支出的双金');
            $table->decimal('poundage', 10, 2)->default(0)->comment('发单收入支出的手续费');
            $table->decimal('take_poundage', 10, 2)->default(0)->comment('接单收入支出的手续费');
            $table->decimal('fine', 10, 2)->default(0)->comment('发单罚款支出');
            $table->decimal('take_fine', 10, 2)->default(0)->comment('接单罚款支出');
            $table->timestamp('order_created_at')->comment('订单发单时间');
            $table->timestamp('order_finished_at')->comment('订单结算时间');
            $table->timestamps();
            $table->decimal('original_amount', 10, 2)->default(0)->comment('来源价格, 默认 0');
            $table->tinyInteger('third')->unsigned()->default(1)->comment('发单平台号,默认1 发单器发单');
            $table->tinyInteger('is_repeat')->unsigned()->default(0)->comment('是否为重发单:0 否, 1 是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statistics');
    }
}
