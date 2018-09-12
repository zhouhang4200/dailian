<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAssetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_assets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->comment('用户ID');
			$table->decimal('balance', 17)->default(0.00)->comment('余额');
			$table->decimal('frozen', 17)->default(0.00)->comment('冻结资金');
			$table->decimal('total_recharge', 22)->default(0.00)->comment('累计充值');
			$table->decimal('total_withdraw', 22)->default(0.00)->comment('累计提现');
			$table->decimal('total_expend', 22)->default(0.00)->comment('累计支出');
			$table->decimal('total_income', 22)->default(0.00)->comment('累计收入');
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
		Schema::drop('user_assets');
	}

}
