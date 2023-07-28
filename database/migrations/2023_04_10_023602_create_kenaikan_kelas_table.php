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
        Schema::create('kenaikan_kelas', function (Blueprint $table) {
            $table->uuid('kenaikan_kelas_id');
			$table->uuid('sekolah_id');
			$table->uuid('anggota_rombel_id');
			$table->string('nama_kelas')->nullable();
			$table->integer('status')->default('2');
			$table->timestamps();
			$table->primary('kenaikan_kelas_id');
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kenaikan_kelas');
    }
};
