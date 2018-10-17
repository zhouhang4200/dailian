<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191)->unique()->comment('账号');
			$table->string('email', 60)->unique()->comment('邮箱');
			$table->string('password', 120)->comment('密码');
			$table->tinyInteger('status')->default(1)->comment('状态 1 启用 2 禁用');
            $table->string('last_login_local', 100)->nullable()->comment('最后登录地');
            $table->dateTime('last_login_at')->nullable()->comment('最后登录时间');
            $table->bigInteger('last_login_ip')->nullable()->comment('最后登录IP');
            $table->string('current_login_local', 100)->nullable()->comment('当前登录地');
            $table->dateTime('current_login_at')->nullable()->comment('当前登录时间');
            $table->bigInteger('current_login_ip')->nullable()->comment('当前登录IP');
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
		Schema::drop('admin_users');
	}

}
