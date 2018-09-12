<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameLevelingOrderPreviousStatusesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_leveling_order_previous_statuses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('game_leveling_order_trade_no', 22)->comment('交易单号');
			$table->integer('status')->unsigned()->comment('代练订单前一个状态');
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
		Schema::drop('game_leveling_order_previous_statuses');
	}

}
