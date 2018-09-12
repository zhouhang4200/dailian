<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('articles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_category_id')->unsigned()->comment('分类ID');
			$table->string('title', 191)->comment('文章标题');
			$table->text('content', 65535)->comment('内容');
			$table->string('link', 500)->comment('连接');
			$table->integer('click_count')->unsigned()->comment('点击数');
			$table->integer('sort')->unsigned()->default(255)->comment('排序');
			$table->boolean('status')->comment('状态，1：开启,2:禁用');
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
		Schema::drop('articles');
	}

}
