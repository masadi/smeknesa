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
        Schema::create('rencana_projek', function (Blueprint $table) {
            $table->uuid('rencana_projek_id');
            $table->uuid('sekolah_id');
            $table->uuid('rombongan_belajar_id');
            $table->uuid('pembelajaran_id');
            $table->foreignId('tema_id')->constrained('tema_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama');
            $table->string('deskripsi');
            $table->smallInteger('no_urut');
            $table->timestamps();
            $table->primary('rencana_projek_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('rencana_projek');
    }
};
