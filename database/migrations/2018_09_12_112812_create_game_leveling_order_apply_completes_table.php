<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameLevelingOrderApplyCompletesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_leveling_order_apply_completes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('game_leveling_order_trade_no', 22)->comment('代练订单交易号');
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
		Schema::drop('game_leveling_order_apply_completes');
	}

}
