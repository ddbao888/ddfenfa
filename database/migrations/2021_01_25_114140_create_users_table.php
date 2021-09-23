<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mg_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name', 20)->comment('用户名');
            $table->index('user_name');
            $table->string('real_name', 20)->nullable();
            $table->string('password', 200);
            $table->string('mobile', 13)->nullable();
            $table->string('wx_openid', 80)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_admin')->default(0);
            $table->integer('version')->default(1);
            $table->integer('role_id')->nullable();
            $table->rememberToken();
            $table->string('ip', 20)->nullable();
            $table->timestamp('login_time')->nullable();
            $table->timestamp('create_time')->nullable();
            $table->integer('unicid');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mg_users');
    }
}
