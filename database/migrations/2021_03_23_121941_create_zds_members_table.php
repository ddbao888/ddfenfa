<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZdsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zds_members', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('unicid');
            $table->index('unicid');
            $table->integer('uid');
            $table->index('uid');
            $table->integer('level_id');
            $table->string('nick_name', 30);
            $table->string('avatar', 200);
            $table->string('wx_openid', 80);
            $table->string('baidu_openid', 80);
            $table->string('douyin_openid', 80);
            $table->string('area');
            $table->integer('sex');
            $table->integer('gold')->default(0);
            $table->decimal('credit', 10, 2)->default(0.00);
            $table->integer('answer_num')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0.00);
            $table->bigInteger('addtime');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('zds_members');
    }
}
