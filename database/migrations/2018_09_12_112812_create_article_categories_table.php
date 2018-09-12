<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticleCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article_categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191)->comment('分类名');
			$table->integer('parent_id')->unsigned()->comment('父分类ID');
			$table->integer('sort')->unsigned()->default(255)->comment('排序');
			$table->boolean('status')->comment('1:开启，2:禁用');
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
		Schema::drop('article_categories');
	}

}
