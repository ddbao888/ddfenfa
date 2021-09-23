<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mg_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->char('icon', 50)->nullable();
            $table->char('icon_active', 50)->nullable();
            $table->tinyInteger('is_icon')->default(0);
            $table->integer('parent_id')->default(0);
            $table->integer('sort')->default(0);
            $table->string('href')->nullable();
            $table->integer('unicid')->default(1);
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
        Schema::dropIfExists('mg_menus');
    }
}
