<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 60)->unique()->comment('游戏名称');
			$table->integer('status')->comment('类型');
			$table->string('initials', 8)->comment('首字母拼音');
			$table->boolean('game_type_id')->comment('游戏类型:手游 端游');
			$table->boolean('game_class_id')->comment('游戏类别: 射击 策略');
			$table->string('icon', 191)->comment('游戏图标');
			$table->tinyIncrements('status')->default(1)->comment('是否显示 1 显示 2 隐藏');
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
		Schema::drop('games');
	}

}
