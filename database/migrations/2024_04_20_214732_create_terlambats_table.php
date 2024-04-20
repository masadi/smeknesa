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
        Schema::create('terlambat', function (Blueprint $table) {
            $table->uuid('terlambat_id');
            $table->uuid('anggota_rombel_id');
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->primary('terlambat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terlambat');
    }
};
