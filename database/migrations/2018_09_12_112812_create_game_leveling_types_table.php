<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameLevelingTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_leveling_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('game_id')->unsigned()->comment('游戏ID');
			$table->string('name', 60)->comment('代练类型名称');
			$table->decimal('poundage')->comment('代练类型手续费');
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
		Schema::drop('game_leveling_types');
	}

}
