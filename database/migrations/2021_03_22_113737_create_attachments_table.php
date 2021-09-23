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
            $table->uuid('uuid');
            $table->index('uuid');
            $table->integer('unicid');
            $table->integer('uid');
            $table->string('title', 100);
            $table->string('url', 300);
            $table->string('path', 300);
            $table->bigInteger('size')->default(0);
            $table->integer('type')->default(0);
            $table->integer('attachment_group_id')->default(0);
            $table->softDeletes();
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
