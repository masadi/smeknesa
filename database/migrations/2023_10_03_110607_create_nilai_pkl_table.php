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
        Schema::create('nilai_pkl', function (Blueprint $table) {
            $table->uuid('nilai_pkl_id');
            $table->uuid('peserta_didik_id');
            $table->uuid('pkl_id');
            $table->uuid('tp_id');
            $table->smallInteger('nilai');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->primary('nilai_pkl_id');
            $table->foreign('peserta_didik_id')->references('peserta_didik_id')->on('peserta_didik')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pkl_id')->references('pkl_id')->on('praktik_kerja_lapangan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tp_id')->references('tp_id')->on('tujuan_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_pkl');
    }
};
