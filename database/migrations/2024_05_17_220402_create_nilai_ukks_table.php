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
        Schema::create('nilai_ukk', function (Blueprint $table) {
            $table->uuid('nilai_ukk_id');
			$table->uuid('sekolah_id');
            $table->uuid('rencana_ukk_id');
			$table->uuid('anggota_rombel_id');
			$table->uuid('peserta_didik_id');
			$table->integer('nilai');
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('rencana_ukk_id')->references('rencana_ukk_id')->on('rencana_ukk')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('anggota_rombel_id')->references('anggota_rombel_id')->on('anggota_rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('peserta_didik_id')->references('peserta_didik_id')->on('peserta_didik')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('nilai_ukk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_ukk');
    }
};
