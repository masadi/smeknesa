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
        Schema::create('elemen_rencana_projek', function (Blueprint $table) {
            $table->uuid('elemen_rencana_id');
            $table->uuid('sekolah_id');
            $table->uuid('rencana_projek_id');
            $table->foreignId('dimensi_id')->constrained('dimensi_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('elemen_id')->constrained('elemen_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->primary('elemen_rencana_id');
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('rencana_projek_id')->references('rencana_projek_id')->on('rencana_projek')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elemen_rencana_projek');
    }
};
