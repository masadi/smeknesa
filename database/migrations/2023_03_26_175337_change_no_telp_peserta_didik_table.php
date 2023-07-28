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
        Schema::table('peserta_didik', function (Blueprint $table) {
            $table->renameColumn('no_telp', 'no_hp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_didik', function (Blueprint $table) {
            $table->renameColumn('no_hp', 'no_telp');
        });
    }
};
