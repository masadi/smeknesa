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
        Schema::create('mapel_tingkat', function (Blueprint $table) {
            $table->integer('mata_pelajaran_id')->nullable();
            $table->integer('tingkat');
            $table->uuid('jurusan_sp_id');
            $table->timestamps();
            $table->foreign('jurusan_sp_id')->references('jurusan_sp_id')->on('jurusan_sp')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('mata_pelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary(['mata_pelajaran_id', 'tingkat', 'jurusan_sp_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mapel_tingkat');
    }
};
