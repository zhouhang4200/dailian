<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trade_no', 22)->comment('关联交易单号');
            $table->string('name')->comment('名称');
            $table->string('mime_type', 100)->comment('mime');
            $table->string('path', 200)->comment('路径');
            $table->string('description', 200)->nullable()->comment('说明');
            $table->string('attachment_type', 200)->comment('多态关联模型');
            $table->unsignedInteger('attachment_id')->comment('多态关联模型ID');
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
        Schema::dropIfExists('attachments');
    }
}
