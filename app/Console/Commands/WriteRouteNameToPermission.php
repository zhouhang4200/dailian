<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Permission;
use Illuminate\Console\Command;

class WriteRouteNameToPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'write:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将权限路由名写入权限表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // 获取所有的权限名
            $permissions = $this->getRoutes();
            // 将权限写入数据库
            if ($permissions) {
                // 自定义模块
                $modules = [
                    'employee' => '账号管理',
                    'take' => '接单管理',
                    'send' => '发单管理',
                    'asset-flow' => '财务管理',
                    'balance-withdraw' => '财务管理',
                    'finance-report-day' => '财务管理',
                ];

                // 别名
                $aliases = [
                    'order.take' => '接单中心',
                    'order.send' => '发单中心',
                    'employee' => '员工管理',
                    'employee.group' => '岗位管理',
                    'asset-flow' => '资金明细',
                    'balance-withdraw' => '我的提现',
                    'finance-report-day' => '资金日报',
                ];

                $data = [];
                foreach ($permissions as $k => $permission) {
                    // 检查是否已经存在权限
                    $has = Permission::where('name', $permission)->first();

                    if (! $has) {
                        // 模块名
                        $index = explode('.', $permission);

                        if (in_array($index[0], array_keys($modules))) {
                            $module = $modules[$index[0]];
                        } elseif (isset($index[1]) && in_array($index[1], array_keys($modules))) {
                            $module = $modules[$index[1]];
                        } else {
                            $module = '未定义';
                        }

                        // 别名
                        $alias = $aliases[$permission] ?? '未知';
                        $data[$k] = [
                            'module_name' => $module,
                            'name' => $permission,
                            'alias' => $alias,
                        ];
                    }
                }
                Permission::insert($data);
            }
        } catch (Exception $e) {
            echo '数据异常';
        }
        echo '写入成功';
    }

    /**
     * 正则匹配到所有的权限名
     * @return array
     */
    public function getRoutes()
    {
        $content = file_get_contents(base_path('routes\front.php'));
        $pattern = "/(middleware\(\'permission\:)(.*)(\'\))/";
        $int = preg_match_all($pattern, $content, $matches);

        if (isset($matches) && count($matches) > 0) {
            return $matches[2];
        }

        return [];
    }
}
