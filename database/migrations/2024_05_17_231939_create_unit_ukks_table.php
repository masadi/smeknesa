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
        Schema::create('unit_ukk', function (Blueprint $table) {
            $table->uuid('unit_ukk_id');
			$table->uuid('paket_ukk_id');
			$table->string('kode_unit')->nullable();
			$table->string('nama_unit')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->foreign('paket_ukk_id')->references('paket_ukk_id')->on('paket_ukk')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->primary('unit_ukk_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_ukk');
    }
};
