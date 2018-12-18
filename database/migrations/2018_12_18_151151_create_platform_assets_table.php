<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('balance', 30)->default(0.00)->comment('平台实时余额');
            $table->decimal('frozen', 30)->default(0.00)->comment('平台实时冻结资金');
            $table->decimal('total_recharge', 30)->default(0.00)->comment('平台累计充值');
            $table->decimal('total_withdraw', 30)->default(0.00)->comment('平台累计提现');
            $table->decimal('total_expend', 30)->default(0.00)->comment('平台累计支出');
            $table->decimal('total_income', 30)->default(0.00)->comment('平台累计收入');
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
        Schema::dropIfExists('platform_assets');
    }
}
