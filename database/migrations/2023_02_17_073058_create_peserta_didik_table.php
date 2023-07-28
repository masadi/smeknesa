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
        Schema::create('peserta_didik', function (Blueprint $table) {
            $table->uuid('peserta_didik_id');
			$table->uuid('sekolah_id');
			$table->string('nama');
			$table->string('no_induk')->nullable();
			$table->string('nisn')->nullable();
			$table->string('nik', 16)->nullable();
			$table->string('jenis_kelamin')->nullable();
			$table->string('tempat_lahir')->nullable();
			$table->date('tanggal_lahir')->nullable();
			$table->integer('agama_id')->nullable();
			$table->string('alamat_jalan')->nullable();
			$table->string('rt')->nullable();
			$table->string('rw')->nullable();
			$table->string('kode_pos')->nullable();
            $table->char('provinsi_id', 2)->nullable();
            $table->char('kabupaten_id', 4)->nullable();
            $table->char('kecamatan_id', 7)->nullable();
            $table->char('desa_id', 10)->nullable();
			$table->string('no_telp')->nullable();
			$table->string('sekolah_asal')->nullable();
			$table->string('diterima_kelas')->nullable();
			$table->date('diterima')->nullable();
			$table->string('email')->nullable();
			$table->string('nama_ayah')->nullable();
			$table->string('nama_ibu')->nullable();
			$table->integer('kerja_ayah')->nullable();
			$table->integer('kerja_ibu')->nullable();
			$table->string('photo')->nullable();
			$table->timestamps();
			$table->softDeletes();
            $table->primary('peserta_didik_id');
            $table->foreign('provinsi_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'provinces')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kabupaten_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'cities')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kecamatan_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'districts')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('desa_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'villages')->onUpdate('cascade')->onDelete('restrict');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('peserta_didik_id')->nullable();
			$table->foreign('peserta_didik_id')->references('peserta_didik_id')->on('peserta_didik')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
		Schema::table('pd_keluar', function (Blueprint $table) {
            $table->foreign('peserta_didik_id')->references('peserta_didik_id')->on('peserta_didik')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['peserta_didik_id']);
			$table->dropColumn('peserta_didik_id');
        });
		Schema::table('pd_keluar', function (Blueprint $table) {
            $table->dropForeign(['peserta_didik_id']);
        });
        Schema::dropIfExists('peserta_didik');
    }
};
