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
            $table->unsignedInteger('status')->default(1)->comment('订单状态');
            $table->string('foreign_trade_no', 60)->comment('外部交易单号');
            $table->unsignedInteger('user_id')->comment('创建订单用户ID');
            $table->string('username')->comment('创建订单用户');
            $table->unsignedInteger('parent_user_id')->comment('创建订单用户父ID');
            $table->string('parent_username')->comment('创建订单用户父');
            $table->unsignedInteger('take_user_id')->default(0)->comment('接单用户ID');
            $table->string('take_username')->nullable()->comment('接单用户名');
            $table->unsignedInteger('take_parent_user_id')->default(0)->comment('接单用户父ID');
            $table->string('take_parent_username')->nullable()->comment('接单用户父用户名');
            $table->unsignedTinyInteger('order_type_id')->comment('订单类型');
            $table->unsignedInteger('game_type_id')->comment('游戏类型ID');
            $table->unsignedInteger('game_class_id')->comment('游戏类别ID');
            $table->unsignedInteger('game_id')->comment('游戏ID');
            $table->string('game_name', 60)->comment('游戏名称');
            $table->unsignedInteger('region_id')->comment('游戏区ID');
            $table->string('region_name', 60)->comment('游戏区名称');
            $table->unsignedInteger('server_id')->comment('游戏服务器ID');
            $table->string('server_name', 60)->comment('游戏服务器名称');
            $table->unsignedInteger('game_leveling_type_id')->comment('游戏代练类型');
            $table->string('game_leveling_type_name')->comment('游戏代练类型名称');
            $table->string('title', 200)->comment('代练标题');
            $table->decimal('amount', 17, 2)->comment('代练金额');
            $table->string('game_account', 100)->comment('游戏账号');
            $table->string('game_password', 500)->comment('游戏密码');
            $table->string('game_role', 100)->comment('游戏角色');
            $table->unsignedInteger('day')->default(0)->comment('代练天数');
            $table->unsignedInteger('hour')->default(0)->comment('代练小时');
            $table->decimal('security_deposit', 17, 2)->default(0)->comment('安全保证金');
            $table->decimal('efficiency_deposit', 17, 2)->default(0)->comment('效率保证金');
            $table->text('explain')->comment('代练说明');
            $table->text('requirement')->comment('代练要求');
            $table->string('take_order_password', 30)->nullable()->comment('接单密码');
            $table->string('player_phone', 20)->default(0)->comment('玩家电话');
            $table->string('player_qq', 20)->default(0)->comment('玩家QQ');
            $table->string('user_phone', 20)->default(0)->comment('发单用户电话');
            $table->string('user_qq', 20)->default(0)->comment('发单用户qq');
            $table->timestamp('take_at')->nullable()->comment('接单时间');
            $table->decimal('price_increase_step', 17, 2)->default(0)->comment('自动加价步长');
            $table->decimal('price_ceiling', 17, 2)->default(0)->comment('自动加价上限');
            $table->timestamp('apply_complete_at')->nullable()->comment('申请验收时间');
            $table->timestamp('complete_at')->nullable()->comment('订单完成时间');
            $table->unsignedTinyInteger('source')->default(1)->comment('订单来源');
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
        Schema::dropIfExists('game_leveling_orders');
    }
}
