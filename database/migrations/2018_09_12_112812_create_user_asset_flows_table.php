<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAssetFlowsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_asset_flows', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->comment('用户ID');
			$table->integer('type')->comment('类型');
			$table->integer('sub_type')->unsigned()->comment('子类型');
			$table->string('trade_no', 22)->comment('交易单号');
			$table->decimal('amount', 17)->comment('发生金额');
			$table->decimal('balance', 17)->comment('发生后余额');
			$table->decimal('frozen', 17)->comment('发生后冻结余额');
			$table->string('remark', 500)->comment('备注');
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
		Schema::drop('user_asset_flows');
	}

}
