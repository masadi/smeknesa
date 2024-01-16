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
        Schema::table('anggota_rombel', function (Blueprint $table) {
            $table->unsignedBigInteger('nilai_formatif')->nullable();
            $table->unsignedBigInteger('nilai_sumatif')->nullable();
            $table->unsignedInteger('rangking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota_rombel', function (Blueprint $table) {
            $table->dropColumn('nilai_formatif');
            $table->dropColumn('nilai_sumatif');
            $table->dropColumn('rangking');
        });
    }
};
