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
        Schema::create('ijin', function (Blueprint $table) {
            $table->uuid('ijin_id');
            $table->uuid('anggota_rombel_id');
            $table->uuid('guru_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('jenis_ijin', 4);
            $table->string('pilihan_ijin', 1);
            $table->string('alasan')->nullable();
            $table->timestamps();
            $table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('ijin_id');
        });
        Schema::table('presensi', function (Blueprint $table) {
            $table->uuid('ijin_id')->nullable();
            $table->foreign('ijin_id')->references('ijin_id')->on('ijin')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropForeign(['ijin_id']);
            $table->dropColumn('ijin_id');
        });
        Schema::dropIfExists('ijin');
    }
};
