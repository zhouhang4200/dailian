<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('route_name', 191);
			$table->integer('admin_permission_group_id')->unsigned()->comment('分组ID');
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
		Schema::drop('admin_permissions');
	}

}
