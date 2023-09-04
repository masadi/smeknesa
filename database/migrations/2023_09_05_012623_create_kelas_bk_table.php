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
        Schema::create('kelas_bk', function (Blueprint $table) {
            $table->uuid('kelas_bk_id');
            $table->uuid('rombongan_belajar_id');
            $table->string('semester_id', 5);
			$table->uuid('guru_id');
            $table->timestamps();
            $table->primary('kelas_bk_id');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('semester')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('kelas_bk');
    }
};
