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
        Schema::create('modul', function (Blueprint $table) {
            $table->uuid('modul_id');
            $table->uuid('pembelajaran_id');
            $table->string('alokasi_waktu');
            $table->string('jumlah_pertemuan');
            $table->string('kata_kunci');
            $table->string('karakteristik_pd');
            $table->string('tujuan_pembelajaran');
            $table->string('berkas');
            $table->timestamps();
            $table->primary('modul_id');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul');
    }
};
