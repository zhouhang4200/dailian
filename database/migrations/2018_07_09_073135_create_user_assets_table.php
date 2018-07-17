<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->decimal('balance', 17, 2)->default(0)->comment('余额');
            $table->decimal('frozen', 17, 2)->default(0)->comment('冻结资金');
            $table->decimal('total_recharge', 22, 2)->default(0)->comment('累计充值');
            $table->decimal('total_withdraw', 22, 2)->default(0)->comment('累计提现');
            $table->decimal('total_expend', 22, 2)->default(0)->comment('累计支出');
            $table->decimal('total_income', 22, 2)->default(0)->comment('累计收入');
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
        Schema::dropIfExists('user_assets');
    }
}
