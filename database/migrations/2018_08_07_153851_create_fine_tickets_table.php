<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFineTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fine_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trade_no', '22')->comment('罚款单号');
            $table->string('relation_trade_no', '22')->nullable()->comment('关联单号');
            $table->decimal('amount', 17, 2)->comment('罚款金额');
            $table->unsignedInteger('user_id')->comment('罚款用户ID主ID');
            $table->string('reason', 500)->comment('罚款原因');
            $table->string('remark', 500)->comment('备注');
            $table->unsignedTinyInteger('status')->default('1')->comment('状态 1 冻结中 2 已解冻 3 已罚款');
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
        Schema::dropIfExists('fine_tickets');
    }
}
