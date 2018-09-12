<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlatformFinanceReportDaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('platform_finance_report_days', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('opening_balance', 17)->comment('期初余额');
			$table->decimal('opening_frozen', 17)->comment('期初冻结');
			$table->decimal('balance', 17)->comment('余额');
			$table->decimal('frozen', 17)->comment('冻结金额');
			$table->decimal('withdraw', 17)->comment('提现金额');
			$table->decimal('recharge', 17)->comment('充值金额');
			$table->decimal('expend', 17)->comment('支出金额');
			$table->decimal('income', 17)->comment('收入金额');
			$table->date('date')->comment('日期');
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
		Schema::drop('platform_finance_report_days');
	}

}
