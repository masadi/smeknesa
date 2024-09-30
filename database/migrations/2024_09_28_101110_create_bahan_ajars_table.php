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
        Schema::create('bahan_ajars', function (Blueprint $table) {
            $table->id();
            $table->uuid('author');
            $table->uuid('jurusan_sp_id');
            $table->integer('tingkat');
            $table->uuid('pembelajaran_id');
            $table->string('judul');
            $table->string('deskripsi');
            $table->string('gambar');
            $table->string('file_pdf');
            $table->unsignedBigInteger('download_count')->nullable()->default(0);
            $table->timestamps();
            $table->foreign('author')->references('user_id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('jurusan_sp_id')->references('jurusan_sp_id')->on('jurusan_sp')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bahan_ajars');
    }
};
