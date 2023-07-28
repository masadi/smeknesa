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
        Schema::table('capaian_pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['pembelajaran_id']);
            $table->dropColumn('pembelajaran_id');
            $table->integer('mata_pelajaran_id')->nullable();
            $table->uuid('guru_id')->nullable();
            $table->foreign('guru_id')->references('guru_id')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('capaian_pembelajaran', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
            $table->uuid('pembelajaran_id')->nullable();
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }
};
