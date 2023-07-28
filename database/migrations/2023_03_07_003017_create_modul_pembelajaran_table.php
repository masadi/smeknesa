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
        Schema::create('modul_pembelajaran', function (Blueprint $table) {
            $table->uuid('modul_pembelajaran_id');
            $table->uuid('modul_id');
            $table->foreignId('model_pembelajaran_id')->constrained('modul_model_pembelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->primary('modul_pembelajaran_id');
			$table->foreign('modul_id')->references('modul_id')->on('modul')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modul_pembelajaran');
    }
};
