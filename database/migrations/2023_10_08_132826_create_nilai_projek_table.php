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
        Schema::create('nilai_projek', function (Blueprint $table) {
            $table->uuid('nilai_projek_id');
            $table->uuid('sekolah_id');
            $table->uuid('anggota_rombel_id');
            $table->uuid('elemen_rencana_id');
            $table->foreignId('dimensi_id')->constrained('dimensi_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('elemen_id')->constrained('elemen_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('opsi_id')->constrained('opsi_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->primary('nilai_projek_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('elemen_rencana_id')->references('elemen_rencana_id')->on('elemen_rencana_projek')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_projek');
    }
};
