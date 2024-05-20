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
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->uuid('dudi_id')->nullable();
            $table->foreign('dudi_id')->references('dudi_id')->on('dudi')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rencana_ukk', function (Blueprint $table) {
            $table->dropForeign(['dudi_id']);
            $table->dropColumn(['dudi_id']);
        });
    }
};
