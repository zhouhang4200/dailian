<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->string('name')->comment('名称');
            $table->unsignedInteger('age')->default(0)->comment('年龄');
            $table->string('email')->comment('邮箱');
            $table->string('phone')->comment('电话号码');
            $table->string('password')->comment('登录密码');
            $table->string('pay_password')->comment('支付密码');;
            $table->string('wechat', 200)->nullable()->comment('微信账号');
            $table->string('qq', 200)->nullable()->comment('qq账号');
            $table->string('qq_open_id', 200)->nullable()->comment('QQOpenID');
            $table->string('wechat_open_id', 200)->nullable()->comment('微信openID');
            $table->string('avatar')->default('/front/images/default_avatar.png')->comment('头像');
            $table->tinyInteger('sex')->default(1)->comment('性别 1 男 2 女');
            $table->dateTime('birthday')->nullable()->comment('生日');
            $table->tinyInteger('status')->default(1)->comment('状态 1 启用 2 禁用 3 已删除');
            $table->string('last_login_local', 100)->nullable()->comment('最后登录地');
            $table->dateTime('last_login_at')->nullable()->comment('最后登录时间');
            $table->bigInteger('last_login_ip')->nullable()->comment('最后登录IP');
            $table->string('current_login_local', 100)->nullable()->comment('当前登录地');
            $table->dateTime('current_login_at')->nullable()->comment('当前登录时间');
            $table->bigInteger('current_login_ip')->nullable()->comment('当前登录IP');
            $table->string('app_id', 60)->nullable()->comment('api app_id');
            $table->string('app_secret', 60)->nullable()->comment('api app_secret');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
