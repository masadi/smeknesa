<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('post_author');
            $table->longText('post_content');
            $table->text('post_title');
            $table->string('post_name');
            $table->string('post_status', 20)->default('publish');
            $table->string('post_type', 20)->default('post');
            $table->string('post_image');
            $table->timestamps();
            $table->foreign('post_author')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
