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
        Schema::create('presensi_jadwal', function (Blueprint $table) {
            $table->uuid('presensi_jadwal_id');
            $table->uuid('presensi_id');
            $table->uuid('jadwal_id');
            $table->timestamps();
            $table->foreign('presensi_id')->references('presensi_id')->on('presensi')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensi_jadwal');
    }
};
