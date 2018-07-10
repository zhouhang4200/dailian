<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameLevelingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_leveling_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trade_no', 22)->comment('交易单号');
            $table->integer('user_id')->comment('创建订单用户');
            $table->integer('parent_user_id')->comment('创建订单用户的父ID');
            $table->tinyInteger('order_type_id')->comment('订单类型');
            $table->integer('game_type_id')->comment('游戏类型ID');
            $table->integer('game_class_id')->comment('游戏类别ID');
            $table->integer('game_id')->comment('游戏ID');
            $table->string('game_name', 60)->comment('游戏名称');
            $table->integer('region_id')->comment('游戏区ID');
            $table->string('region_name', 60)->comment('游戏区名称');
            $table->integer('server_id')->comment('游戏服务器ID');
            $table->string('server_name', 60)->comment('游戏服务器名称');
            $table->integer('game_leveling_type_id')->comment('游戏代练类型');
            $table->string('title', 200)->comment('代练标题');
            $table->string('game_account', 100)->comment('游戏账号');
            $table->string('game_password', 500)->comment('游戏密码');
            $table->string('game_role', 100)->comment('游戏角色');
            $table->decimal('security_deposit', 17, 2)->comment('安全保证金');
            $table->decimal('efficiency_deposit', 17, 2)->comment('效率保证金');
            $table->text('explain')->comment('代练说明');
            $table->text('requirement')->comment('代练要求');
            $table->string('take_order_password', 30)->comment('接单密码');
            $table->string('player_phone', 20)->comment('玩家电话');
            $table->string('player_qq', 20)->comment('玩家QQ');
            $table->string('user_phone', 20)->comment('发单用户电话');
            $table->string('user_qq', 20)->comment('发单用户qq');
            $table->integer('status')->comment('订单状态');
            $table->integer('take_user_id')->comment('接单用户ID');
            $table->integer('parent_take_user_id')->comment('接单用户父ID');
            $table->dateTime('take_at')->comment('接单时间');
            $table->decimal('price_increase_step', 17, 2)->comment('自动加价步长');
            $table->decimal('price_ceiling', 17, 2)->comment('自动加价上限');
            $table->dateTime('created_at')->comment('下单时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('complete_at')->comment('订单完成时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_leveling_orders');
    }
}
