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
        Schema::create('langkah_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('langkah_id')->nullable();
            $table->string('nama');
            $table->timestamps();
            $table->foreign('langkah_id')->references('id')->on('langkah_pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('langkah_pembelajaran');
    }
};
