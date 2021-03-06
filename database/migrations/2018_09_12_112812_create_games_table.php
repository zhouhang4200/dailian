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
			$table->integer('sort', 60)->default('9999')->comment('显示排序');
			$table->string('initials', 8)->comment('首字母拼音');
			$table->integer('game_type_id')->comment('游戏类型:手游 端游');
			$table->integer('game_class_id')->comment('游戏类别: 射击 策略');
			$table->string('icon', 191)->comment('游戏图标');
			$table->tinyInteger('status')->default(1)->comment('是否显示 1 显示 2 隐藏');
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
