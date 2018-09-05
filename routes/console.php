<?php

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('finance:user-report-day', function () {
    $this->comment(\App\Console\Commands\PlatformFinanceReportDayCommand::class);
})->describe('平台资金日报表');

Artisan::command('passport:install', function () {
    $this->comment(\Laravel\Passport\Console\InstallCommand::class);
})->describe('安装 passport');
