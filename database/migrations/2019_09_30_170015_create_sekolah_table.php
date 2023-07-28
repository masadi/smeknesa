<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->uuid('sekolah_id');
			$table->string('npsn');
			$table->string('nama');
			$table->string('nss')->nullable();
			$table->string('alamat_jalan')->nullable();
			$table->char('provinsi_id', 2)->nullable();
            $table->char('kabupaten_id', 4)->nullable();
            $table->char('kecamatan_id', 7)->nullable();
            $table->char('desa_id', 10)->nullable();
			$table->string('kode_pos')->nullable();
			$table->string('lintang')->nullable();
			$table->string('bujur')->nullable();
			$table->string('no_telp')->nullable();
			$table->string('no_fax')->nullable();
			$table->string('email')->nullable();
			$table->string('website')->nullable();
			$table->integer('bentuk_pendidikan_id');
			$table->integer('status_sekolah');
			$table->timestamps();
			$table->primary('sekolah_id');
            $table->foreign('provinsi_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'provinces')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kabupaten_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'cities')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kecamatan_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'districts')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('desa_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'villages')->onUpdate('cascade')->onDelete('restrict');
        });
		Schema::table('users', function (Blueprint $table) {
            $table->uuid('sekolah_id')->nullable();
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['sekolah_id']);
			$table->dropColumn('sekolah_id');
        });
        Schema::dropIfExists('sekolah');
    }
}
