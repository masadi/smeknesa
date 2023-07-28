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
        Schema::create('kelompok', function (Blueprint $table) {
            $table->increments('kelompok_id');
            $table->string('nama_kelompok');
			$table->integer('kurikulum');
            $table->timestamps();
        });
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->unsignedBigInteger('kelompok_id')->nullable();
            $table->integer('no_urut')->nullable();
            $table->foreign('kelompok_id')->references('kelompok_id')->on('kelompok')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->integer('tahun')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->dropColumn(['tahun']);
        });
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn(['kelompok_id', 'no_urut']);
        });
        Schema::dropIfExists('kelompok');
    }
};
