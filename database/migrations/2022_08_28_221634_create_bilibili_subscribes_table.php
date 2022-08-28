<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bilibili_subscribes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->comment('主键');
            $table->BigInteger('chat_id')->default(0)->comment('聊天ID');
            $table->unsignedBigInteger('mid')->default(0)->comment('Bilibili UID');
            $table->timestamp('created_at')->useCurrent()->comment('创建时间');
            $table->timestamp('updated_at')->useCurrent()->comment('更新时间');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bilibili_subscribes');
    }
};