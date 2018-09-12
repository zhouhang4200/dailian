<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlockadeAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blockade_accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->comment('用户id');
			$table->boolean('type')->default(1)->comment('封号类型：1-普通封号，2-永久封号，3-解封');
			$table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('封号开始时间');
			$table->dateTime('end_time')->nullable()->comment('封号结束时间');
			$table->string('reason', 500)->default('')->comment('封号原因');
			$table->string('remark', 500)->nullable()->default('')->comment('备注');
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
		Schema::drop('blockade_accounts');
	}

}
