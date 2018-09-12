<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameLevelingOrderMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_leveling_order_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('game_leveling_order_trade_no', 191)->comment('交易单号');
			$table->integer('initiator')->unsigned()->comment('留言发送人 1 发单方 2 接单方 3 平台工作人员');
			$table->integer('from_user_id')->unsigned()->comment('留言发送人ID');
			$table->integer('from_parent_user_id')->unsigned()->comment('留言发送人父ID');
			$table->string('from_username', 191)->comment('留言发送人名字');
			$table->integer('to_user_id')->unsigned()->comment('留言接收人ID');
			$table->string('to_username', 191)->comment('留言接收人名字');
			$table->string('content', 191)->comment('内容');
			$table->string('type', 191)->comment('1 仲裁留言 2 普通订单留言');
			$table->string('status', 191)->default('1')->comment('1未读 2 已读');
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
		Schema::drop('game_leveling_order_messages');
	}

}
