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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->bigIncrements('mata_pelajaran_id');
            $table->string('nama');
            $table->jsonb('tingkat');
            $table->timestamps();
        });
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->integer('mata_pelajaran_id')->nullable();
            $table->foreign('mata_pelajaran_id')->references('mata_pelajaran_id')->on('mata_pelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
        });
        Schema::dropIfExists('mata_pelajaran');
    }
};
