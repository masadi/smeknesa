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
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->bigIncrements('pekerjaan_id');
            $table->string('nama');
            $table->timestamps();
        });
        /*Schema::table('peserta_didik', function (Blueprint $table) {
            $table->foreign('kerja_ayah')->references('pekerjaan_id')->on('pekerjaan')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('kerja_ibu')->references('pekerjaan_id')->on('pekerjaan')->onUpdate('CASCADE')->onDelete('CASCADE');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('peserta_didik', function (Blueprint $table) {
            $table->dropForeign(['kerja_ayah']);
            $table->dropForeign(['kerja_ibu']);
        });*/
        Schema::dropIfExists('pekerjaan');
    }
};
