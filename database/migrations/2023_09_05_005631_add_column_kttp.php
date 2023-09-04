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
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->smallInteger('kktp')->nullable();
        });
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->smallInteger('kktp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropColumn('kktp');
        });
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropColumn('kktp');
        });
    }
};
