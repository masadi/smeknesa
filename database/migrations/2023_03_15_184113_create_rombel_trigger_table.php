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
        Schema::create('rombel_trigger', function (Blueprint $table) {
            $table->uuid('rombel_trigger_id');
            $table->uuid('id_rombel_lama');
            $table->uuid('id_rombel_baru');
            $table->timestamps();
            $table->foreign('id_rombel_lama')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('id_rombel_baru')->references('rombongan_belajar_id')->on('rombongan_belajar')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('rombel_trigger_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rombel_trigger');
    }
};
