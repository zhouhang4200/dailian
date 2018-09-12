<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameLevelingOrderAnomaliesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_leveling_order_anomalies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('trade_no', 191)->comment('订单号');
			$table->string('reason', 300)->comment('异常原因');
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
		Schema::drop('game_leveling_order_anomalies');
	}

}
