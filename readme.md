
## 安装步骤
- cp .env.example .env
- 设置数据库连接,设置elastic 服务器地址
- php artisan migrate
- php artisan elastic:create-index "App\Models\GameLevelingOrderIndexConfigurator"
- php artisan wz:init

