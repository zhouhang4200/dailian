<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->unsigned()->default(0);
			$table->string('name', 191)->comment('名称');
			$table->integer('age')->unsigned()->default(0)->comment('年龄');
			$table->string('email', 191)->nullable()->default('')->comment('邮箱');
			$table->string('phone', 191)->comment('电话号码');
			$table->string('contact_phone', 16)->nullable()->comment('qq账号');
			$table->string('password', 191)->comment('登录密码');
			$table->string('pay_password', 191)->nullable()->default('')->comment('支付密码');
			$table->string('wechat', 200)->nullable()->comment('微信账号');
			$table->string('qq', 200)->nullable()->comment('qq账号');
			$table->string('qq_open_id', 200)->nullable()->comment('QQOpenID');
			$table->string('wechat_open_id', 200)->nullable()->comment('微信openID');
			$table->string('avatar', 191)->default('/front/images/default_avatar.png')->comment('头像');
			$table->boolean('sex')->default(1)->comment('性别 1 男 2 女');
			$table->dateTime('birthday')->nullable()->comment('生日');
			$table->boolean('status')->default(1)->comment('状态 1 启用 2 禁用 3 已删除');
			$table->string('last_login_local', 100)->nullable()->comment('最后登录地');
			$table->dateTime('last_login_at')->nullable()->comment('最后登录时间');
			$table->bigInteger('last_login_ip')->nullable()->comment('最后登录IP');
			$table->string('current_login_local', 100)->nullable()->comment('当前登录地');
			$table->string('signature', 100)->nullable()->comment('签名');
			$table->dateTime('current_login_at')->nullable()->comment('当前登录时间');
			$table->bigInteger('current_login_ip')->nullable()->comment('当前登录IP');
			$table->string('app_id', 60)->nullable()->comment('api app_id');
			$table->string('app_secret', 60)->nullable()->comment('api app_secret');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
