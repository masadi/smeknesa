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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->uuid('penilaian_id');
            $table->foreignId('jenis_penilaian_id')->constrained('jenis_penilaian')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid('pembelajaran_id');
            $table->string('nama')->nullable();
            $table->timestamps();
            $table->foreign('pembelajaran_id')->references('pembelajaran_id')->on('pembelajaran')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary('penilaian_id');
        });
        Schema::table('nilai', function (Blueprint $table) {
            $table->uuid('penilaian_id')->nullable();
            $table->foreign('penilaian_id')->references('penilaian_id')->on('penilaian')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['penilaian_id']);
            $table->dropColumn('penilaian_id');
        });
        Schema::dropIfExists('penilaian');
    }
};
