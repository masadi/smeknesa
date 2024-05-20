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
        Schema::create('jam_terlambat', function (Blueprint $table) {
            $table->uuid('jam_terlambat_id');
            $table->uuid('terlambat_id');
            $table->unsignedTinyInteger('jam');
            $table->foreign('terlambat_id')->references('terlambat_id')->on('terlambat')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
            $table->primary('jam_terlambat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jam_terlambat');
    }
};
