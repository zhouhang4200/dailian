<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBalanceRechargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('balance_recharges', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->comment('用户ID');
			$table->string('trade_no', 22)->comment('交易单号');
			$table->decimal('amount', 17)->comment('交易单号');
			$table->boolean('source')->comment('充值来源 1 微信 2支付宝 3 转账');
			$table->boolean('status')->default(1)->comment('1 创建订单 2 完成充值');
			$table->string('remark', 500)->nullable()->comment('备注');
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
		Schema::drop('balance_recharges');
	}

}
