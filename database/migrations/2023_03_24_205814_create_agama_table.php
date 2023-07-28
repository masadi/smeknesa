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
        Schema::create('agama', function (Blueprint $table) {
            $table->bigIncrements('agama_id');
            $table->string('nama');
            $table->timestamps();
        });
        Schema::table('guru', function (Blueprint $table) {
            $table->foreign('agama_id')->references('agama_id')->on('agama')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['agama_id']);
        });
        Schema::dropIfExists('agama');
    }
};
