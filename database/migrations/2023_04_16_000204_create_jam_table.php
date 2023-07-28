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
        Schema::create('jam', function (Blueprint $table) {
            $table->uuid('jam_id');
            $table->uuid('jadwal_id');
            $table->unsignedSmallInteger('jam');
            $table->timestamps();
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('jam_id');
        });
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('jam');
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
            $table->unsignedSmallInteger('jam')->nullable();
        });
        Schema::dropIfExists('jam');
    }
};
