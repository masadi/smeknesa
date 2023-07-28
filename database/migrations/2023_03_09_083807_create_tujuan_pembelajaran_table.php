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
        Schema::create('tujuan_pembelajaran', function (Blueprint $table) {
            $table->uuid('tp_id');
            $table->uuid('cp_id');
            $table->text('deskripsi');
            $table->timestamps();
            $table->foreign('cp_id')->references('cp_id')->on('capaian_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('tp_id');
        });
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['cp_id']);
            $table->dropColumn('cp_id');
            $table->uuid('tp_id')->nullable();
            $table->foreign('tp_id')->references('tp_id')->on('tujuan_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->uuid('cp_id')->nullable();
            $table->foreign('cp_id')->references('cp_id')->on('capaian_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->dropForeign(['tp_id']);
            $table->dropColumn('tp_id');
        });
        Schema::dropIfExists('tujuan_pembelajaran');
    }
};
