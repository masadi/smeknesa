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
        Schema::table('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->uuid('pembelajaran_id')->nullable();
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
        Schema::table('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->dropForeign(['pembelajaran_id']);
            $table->dropColumn('pembelajaran_id');
        });
    }
};
