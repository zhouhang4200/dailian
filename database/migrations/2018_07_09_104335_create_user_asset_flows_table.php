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
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedTinyInteger('type')->comment('类型');
            $table->unsignedInteger('sub_type')->comment('子类型');
            $table->string('trade_no', 22)->comment('交易单号');
            $table->decimal('amount', 17 ,2)->comment('发生金额');
            $table->decimal('balance', 17 ,2)->comment('发生后余额');
            $table->decimal('frozen', 17 ,2)->comment('发生后冻结余额');
            $table->string('remark', 500)->comment('备注');
            $table->date('date')->comment('日期');
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
        Schema::dropIfExists('user_asset_flows');
    }
}
