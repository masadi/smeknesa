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
        Schema::create('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->uuid('pkl_id');
            $table->uuid('guru_id');
            $table->uuid('dudi_id');
            $table->string('instruktur')->nullable();
            $table->uuid('rombongan_belajar_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('semester_id', 5);
            $table->timestamps();
            $table->primary('pkl_id');
            $table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('semester')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('praktik_kerja_lapangan');
    }
};
