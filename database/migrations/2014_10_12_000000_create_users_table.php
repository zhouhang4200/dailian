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
            $table->integer('parent_id')->default(0);
            $table->string('name')->comment('名称');
            $table->string('email')->comment('邮箱');
            $table->string('phone')->comment('电话号码');
            $table->string('password')->comment('登录密码');
            $table->string('pay_password')->comment('支付密码');;
            $table->string('qq_open_id', 200)->comment('QQOpenID');
            $table->string('wx_open_id', 200)->comment('微信openID');
            $table->string('avatar')->default('')->comment('头像');
            $table->tinyInteger('sex')->default(1)->comment('性别 1 男 2 女');
            $table->dateTime('birthday')->comment('生日');
            $table->tinyInteger('status')->default(1)->comment('状态 1 启用 2 禁用 3 已删除');
            $table->string('last_login_local', 100)->comment('最后登录地');
            $table->dateTime('last_login_at')->comment('最后登录时间');
            $table->bigInteger('last_login_ip')->comment('最后登录IP');
            $table->string('current_login_local', 100)->comment('当前登录地');
            $table->dateTime('current_login_at')->comment('当前登录时间');
            $table->bigInteger('current_login_ip')->comment('当前登录IP');
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
