<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanceWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('trade_no', 22)->comment('交易单号');
            $table->decimal('amount', 17, 2)->comment('金额');
            $table->string('real_name', 60)->comment('开户名');
            $table->string('bank_card', 60)->comment('开户名');
            $table->string('bank_name', 200)->comment('开户行');
            $table->unsignedInteger('status')->comment('状态 1 审核中 2 提成 3 失败');
            $table->string('remark')->comment('失败原因');
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
        Schema::dropIfExists('balance_withdraws');
    }
}
