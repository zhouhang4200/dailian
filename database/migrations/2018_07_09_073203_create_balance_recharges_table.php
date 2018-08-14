<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceRechargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_recharges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('trade_no', 22)->comment('交易单号');
            $table->decimal('amount', 17, 4)->comment('交易单号');
            $table->tinyInteger('source')->comment('充值来源 1 支付宝 2微信 3 转账');
            $table->tinyInteger('status')->comment('1 创建订单 2 完成充值');
            $table->string('remark', 500)->nullable()->comment('备注');
            $table->string('remark', 500)->comment('备注');
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
        Schema::dropIfExists('balance_recharges');
    }
}
