<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAssetFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_asset_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('type')->comment('类型');
            $table->integer('sub_type')->comment('子类型');
            $table->string('trade_no', 22)->comment('交易单号');
            $table->decimal('amount', 17 ,2)->comment('发生金额');
            $table->decimal('balance', 17 ,2)->comment('发生后余额');
            $table->decimal('frozen', 17 ,2)->comment('发生后冻结余额');
            $table->string('remark', 500)->comment('备注');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_asset_flows');
    }
}
