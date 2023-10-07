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
        Schema::table('dudi', function (Blueprint $table) {
            $table->string('nama')->change();
            $table->string('pimpinan')->change();
			$table->string('nama_bidang_usaha')->change();
			$table->string('alamat_jalan')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
