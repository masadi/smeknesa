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
        Schema::create('asesor', function (Blueprint $table) {
            $table->uuid('asesor_id');
            $table->string('nama', 100);
            $table->uuid('dudi_id');
            $table->foreign('dudi_id')->references('dudi_id')->on('dudi')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
            $table->primary('asesor_id');
        });
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->uuid('asesor_id')->nullable();
            $table->foreign('asesor_id')->references('asesor_id')->on('asesor')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->dropForeign(['asesor_id']);
            $table->dropColumn(['asesor_id']);
        });
        Schema::dropIfExists('asesor');
    }
};
