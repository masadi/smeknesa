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
        Schema::table('jadwal', function (Blueprint $table) {
            $table->uuid('rombongan_belajar_id');
            $table->string('semester_id', 5)->nullable();
            $table->foreign('semester_id')->references('semester_id')->on('semester')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rombongan_belajar_id')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['rombongan_belajar_id']);
            $table->dropColumn('rombongan_belajar_id');
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });
    }
};
