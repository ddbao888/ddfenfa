<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mg_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->integer('menu_id')->unsigned();
            $table->tinyInteger('is_show')->default(1);
            $table->tinyInteger('is_add')->default(1);
            $table->tinyInteger('is_edit' )->default(1);
            $table->tinyInteger('is_del')->default(1);
            $table->integer('unicid');
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
        Schema::dropIfExists('mg_permissions');
    }
}
