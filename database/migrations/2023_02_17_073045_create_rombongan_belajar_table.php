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
        Schema::create('rombongan_belajar', function (Blueprint $table) {
            $table->uuid('rombongan_belajar_id');
			$table->uuid('sekolah_id');
			$table->string('semester_id', 5);
			$table->uuid('jurusan_sp_id')->nullable();
            $table->string('kurikulum');
			$table->string('nama');
			$table->uuid('guru_id');
			$table->integer('tingkat');
			$table->timestamps();
			$table->primary('rombongan_belajar_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('semester')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurusan_sp_id')->references('jurusan_sp_id')->on('jurusan_sp')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['rombongan_belajar_id']);
        });
        Schema::dropIfExists('rombongan_belajar');
    }
};
