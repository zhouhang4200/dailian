<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBalanceWithdrawsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('balance_withdraws', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->comment('用户ID');
			$table->string('trade_no', 22)->comment('交易单号');
			$table->decimal('amount', 17)->comment('提现金额');
			$table->decimal('real_amount', 17)->comment('实际到账金额');
			$table->decimal('poundage', 17)->comment('手续费');
			$table->string('real_name', 60)->default('')->comment('开户名');
			$table->string('bank_card', 60)->nullable()->default('')->comment('开户名');
			$table->string('bank_name', 200)->nullable()->default('')->comment('开户行');
			$table->string('alipay_account', 200)->nullable()->comment('开户行');
			$table->integer('status')->unsigned()->comment('状态 1 审核中 2 提成 3 失败');
			$table->string('remark', 191)->nullable()->comment('失败原因');
			$table->timestamps();
			$table->string('alipay_name', 200)->nullable()->comment('开户行');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('balance_withdraws');
	}

}
