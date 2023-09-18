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
        Schema::create('nilai_ekstra', function (Blueprint $table) {
            $table->uuid('nilai_id');
            $table->uuid('materi_id');
            $table->uuid('anggota_rombel_id');
            $table->uuid('rombongan_belajar_id');
            $table->unsignedInteger('angka');
            $table->timestamps();
            $table->primary('nilai_id');
            $table->foreign('materi_id')->references('materi_id')->on('materi_ekstra')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('nilai_ekstra');
    }
};
