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
        Schema::create('guru', function (Blueprint $table) {
            $table->uuid('guru_id');
			$table->uuid('sekolah_id');
			$table->string('nama');
			$table->string('nuptk')->nullable();
			$table->string('nip')->nullable();
			$table->string('jenis_kelamin')->nullable();
			$table->string('tempat_lahir')->nullable();
			$table->date('tanggal_lahir')->nullable();
			$table->string('nik', 16)->nullable();
			$table->integer('jenis_ptk_id')->nullable();
			$table->integer('agama_id')->nullable();
			$table->integer('status_kepegawaian_id')->nullable();
			$table->string('alamat_jalan')->nullable();
			$table->string('rt')->nullable();
			$table->string('rw')->nullable();
			$table->string('kode_pos')->nullable();
            $table->char('provinsi_id', 2)->nullable();
            $table->char('kabupaten_id', 4)->nullable();
            $table->char('kecamatan_id', 7)->nullable();
            $table->char('desa_id', 10)->nullable();
			$table->string('no_hp')->nullable();
			$table->string('email')->nullable();
			$table->string('photo')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('guru_id');
            $table->foreign('provinsi_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'provinces')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kabupaten_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'cities')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('kecamatan_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'districts')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('desa_id')->references('code')->on(config('laravolt.indonesia.table_prefix').'villages')->onUpdate('cascade')->onDelete('restrict');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('guru_id')->nullable();
			$table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
		Schema::table('pembelajaran', function (Blueprint $table) {
            $table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['guru_id']);
			$table->dropColumn('guru_id');
        });
		Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
        });
        Schema::dropIfExists('guru');
    }
};
