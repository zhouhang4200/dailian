<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameLevelingOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_leveling_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('trade_no', 22)->comment('交易单号');
			$table->integer('status')->unsigned()->default(1)->comment('订单状态');
			$table->string('foreign_trade_no', 60)->comment('外部交易单号');
			$table->integer('user_id')->unsigned()->comment('创建订单用户ID');
			$table->string('username', 191)->comment('创建订单用户');
			$table->string('user_phone', 20)->nullable()->default('0')->comment('发单用户电话');
			$table->string('user_qq', 20)->nullable()->default('0')->comment('发单用户qq');
			$table->integer('parent_user_id')->unsigned()->comment('创建订单用户父ID');
			$table->string('parent_username', 191)->comment('创建订单用户父');
			$table->integer('take_user_id')->unsigned()->default(0)->comment('接单用户ID');
			$table->string('take_username', 191)->nullable()->comment('接单用户名');
			$table->integer('take_parent_user_id')->unsigned()->default(0)->comment('接单用户父ID');
			$table->string('take_parent_username', 191)->nullable()->comment('接单用户父用户名');
			$table->integer('order_type_id')->comment('订单类型');
			$table->integer('game_type_id')->unsigned()->comment('游戏类型ID');
			$table->integer('game_class_id')->unsigned()->comment('游戏类别ID');
			$table->integer('game_id')->unsigned()->comment('游戏ID');
			$table->string('game_name', 60)->comment('游戏名称');
			$table->integer('region_id')->unsigned()->comment('游戏区ID');
			$table->string('region_name', 60)->comment('游戏区名称');
			$table->integer('server_id')->unsigned()->comment('游戏服务器ID');
			$table->string('server_name', 60)->comment('游戏服务器名称');
			$table->integer('game_leveling_type_id')->unsigned()->comment('游戏代练类型');
			$table->string('game_leveling_type_name', 191)->comment('游戏代练类型名称');
			$table->string('title', 200)->comment('代练标题');
			$table->decimal('amount', 17)->comment('代练金额');
			$table->string('game_account', 100)->comment('游戏账号');
			$table->string('game_password', 500)->comment('游戏密码');
			$table->string('game_role', 100)->comment('游戏角色');
			$table->integer('day')->unsigned()->default(0)->comment('代练天数');
			$table->integer('hour')->unsigned()->default(0)->comment('代练小时');
			$table->decimal('security_deposit', 17)->default(0.00)->comment('安全保证金');
			$table->decimal('efficiency_deposit', 17)->default(0.00)->comment('效率保证金');
			$table->text('explain')->comment('代练说明');
			$table->text('requirement')->comment('代练要求');
			$table->string('take_order_password', 30)->nullable()->comment('接单密码');
			$table->string('player_name', 80)->default('0')->comment('玩家名称');
			$table->string('player_phone', 20)->default('0')->comment('玩家电话');
			$table->string('player_qq', 20)->default('0')->comment('玩家QQ');
			$table->dateTime('take_at')->nullable()->comment('接单时间');
			$table->decimal('price_increase_step', 17)->default(0.00)->comment('自动加价步长');
			$table->decimal('price_ceiling', 17)->default(0.00)->comment('自动加价上限');
			$table->dateTime('apply_complete_at')->nullable()->comment('申请验收时间');
			$table->dateTime('complete_at')->nullable()->comment('订单完成时间');
			$table->integer('source')->default(1)->comment('订单来源');
			$table->tinyInteger('top')->default(0)->comment('置顶 0 没有置 1 置顶');
			$table->dateTime('top_at')->nullable()->comment('置顶的时间');
			$table->timestamps();
			$table->string('parent_user_phone', 60)->nullable()->default('')->comment('创建订单用户父');
			$table->string('parent_user_qq', 60)->nullable()->default('')->comment('创建订单用户父');
			$table->string('take_user_qq', 60)->nullable()->default('0')->comment('接单用户ID');
			$table->string('take_user_phone', 60)->nullable()->default('')->comment('接单用户ID');
			$table->string('take_parent_phone', 60)->nullable()->default('')->comment('接单用户ID');
			$table->string('take_parent_qq', 60)->nullable()->default('')->comment('接单用户ID');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('game_leveling_orders');
	}

}
