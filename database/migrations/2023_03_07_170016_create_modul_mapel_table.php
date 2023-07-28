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
        Schema::create('modul_mapel', function (Blueprint $table) {
            $table->uuid('modul_mapel_id');
            $table->uuid('modul_id');
            $table->uuid('pembelajaran_id');
            $table->timestamps();
            $table->primary('modul_mapel_id');
            $table->foreign('modul_id')->references('modul_id')->on('modul')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_mapel');
    }
};
