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
        Schema::create('catatan_projek', function (Blueprint $table) {
            $table->uuid('catatan_projek_id');
            $table->uuid('sekolah_id');
            $table->uuid('rencana_projek_id');
            $table->uuid('anggota_rombel_id');
            $table->text('catatan');
            $table->timestamps();
            $table->primary('catatan_projek_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rencana_projek_id')->references('rencana_projek_id')->on('rencana_projek')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catatan_projek');
    }
};
