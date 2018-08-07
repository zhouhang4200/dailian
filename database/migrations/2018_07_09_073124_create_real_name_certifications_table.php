<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRealNameCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('real_name_certifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('real_name', 60)->comment('真实姓名');
            $table->string('identity_card', 60)->comment('身份证号');
            $table->string('identity_card_front', 500)->comment('身份证正面照');
            $table->string('identity_card_back', 500)->comment('身份证反面照');
            $table->string('identity_card_hand', 500)->comment('手持身份证照');
            $table->string('bank_card', 60)->comment('银行卡');
            $table->string('bank_name', 200)->comment('开户行');
            $table->unsignedTinyInteger('status')->comment('状态 1 正在审核 2 通过 3 未通过');
            $table->string('remark', 500)->comment('审核没通过时原因');
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
        Schema::dropIfExists('real_name_certifications');
    }
}
