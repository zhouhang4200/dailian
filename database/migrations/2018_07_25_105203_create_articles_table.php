<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_category_id')->unsigned()->comment('分类ID');
            $table->string('title')->comment('文章标题');
            $table->text('content')->comment('内容');
            $table->string('link', 500)->comment('连接');
            $table->integer('click_count')->unsigned()->comment('点击数');
            $table->integer('sort')->default(255)->unsigned()->comment('排序');
            $table->tinyInteger('status')->unsigned()->comment('状态，1：开启,2:禁用');
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
        Schema::dropIfExists('articles');
    }
}
