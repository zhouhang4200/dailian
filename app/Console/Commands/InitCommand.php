<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wz:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createFrontDefaultPermissionRole();
        $this->info('前台默认权限与角色创建完成');
        Artisan::call('passport:install');
        $this->info('passport 安装完成');
    }

    /**
     * 初始化前台权限与角色
     */
    public function createFrontDefaultPermissionRole()
    {
        if (! Role::where('user_id', 0)->exists()) {
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

            $role = \App\Models\Role::where('name', 'default')->where('user_id', 0)->first();
            $permissionIds = \App\Models\Permission::pluck('id')->toArray();
            $role->permissions()->sync($permissionIds);
        }
    }
}
