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
            $table->string('trade_no', '22')->nullable()->comment('交易单号');
            $table->decimal('amount', 17, 2)->comment('罚款金额');
            $table->decimal('user_id', 17, 2)->comment('罚款用户ID主ID');
            $table->string('result', 500)->comment('罚款原因');
            $table->string('reason', 500)->comment('罚款原因');
            $table->unsignedTinyInteger('status')->comment('状态 1 冻结中 2 已解冻 3 已罚款');
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
