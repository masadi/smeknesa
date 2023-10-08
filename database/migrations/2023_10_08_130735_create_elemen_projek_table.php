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
        Schema::create('elemen_projek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimensi_id')->nullable()->constrained('dimensi_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('elemen_id')->nullable()->constrained('elemen_projek')->onUpdate('cascade')->onDelete('cascade');
            $table->text('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elemen_projek');
    }
};
