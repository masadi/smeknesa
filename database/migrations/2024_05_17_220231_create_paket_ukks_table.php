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
        Schema::create('paket_ukk', function (Blueprint $table) {
            $table->uuid('paket_ukk_id');
			$table->uuid('sekolah_id')->nullable();
			$table->uuid('jurusan_sp_id');
			$table->integer('kurikulum_id');
            $table->integer('kode_kompetensi')->nullable();
			$table->string('nomor_paket')->nullable();
			$table->string('nama_paket_id')->nullable();
			$table->string('nama_paket_en')->nullable();
			$table->integer('status')->default('1');
			$table->integer('jenis_data')->default('1');
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('jurusan_sp_id')->references('jurusan_sp_id')->on('jurusan_sp')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kurikulum_id')->references('kurikulum_id')->on('kurikulum')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('paket_ukk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paket_ukk');
    }
};
