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
        Schema::create('dudi', function (Blueprint $table) {
            $table->uuid('dudi_id');
			$table->uuid('sekolah_id');
			$table->string('nama', 100);
            $table->string('pimpinan', 100);
			$table->string('nama_bidang_usaha', 40);
			$table->string('alamat_jalan', 80);
            $table->timestamps();
            $table->primary('dudi_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->foreign('dudi_id')->references('dudi_id')->on('dudi')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->dropForeign(['dudi_id']);
        });
        Schema::dropIfExists('dudi');
    }
};
