<?php

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_name', 50)->comment('模块名');
            $table->string('name', 50)->comment('权限英文名称');
            $table->string('alias',50)->comment('权限中文名称');
        });

        $data = [
            ['module_name' => '账号管理', 'name' => 'employee', 'alias' => '员工管理'],
            ['module_name' => '账号管理', 'name' => 'employee.group', 'alias' => '岗位管理'],
            ['module_name' => '财务管理', 'name' => 'finance.asset-flow', 'alias' => '资金明细'],
            ['module_name' => '财务管理', 'name' => 'finance.balance-withdraw', 'alias' => '提现记录'],
            ['module_name' => '财务管理', 'name' => 'finance.finance-report-day', 'alias' => '资金日报'],
            ['module_name' => '财务管理', 'name' => 'finance.balance-recharge.record', 'alias' => '充值记录'],
            ['module_name' => '订单管理', 'name' => 'order.take-list', 'alias' => '接单列表']
        ];

        DB::table('permissions')->insert($data);
        DB::table('roles')->insert([['user_id' => 0, 'name' => 'default', 'alias' => '默认权限']]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
