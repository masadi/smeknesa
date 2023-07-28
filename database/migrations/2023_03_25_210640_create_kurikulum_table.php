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
        Schema::create('kurikulum', function (Blueprint $table) {
            $table->integer('kurikulum_id');
            $table->string('nama', 100);
            $table->timestamps();
            $table->primary('kurikulum_id');
        });
        Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->integer('kurikulum_id')->nullable();
            $table->foreign('kurikulum_id')->references('kurikulum_id')->on('kurikulum')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rombongan_belajar', function (Blueprint $table) {
            $table->dropForeign(['kurikulum_id']);
            $table->dropColumn('kurikulum_id');
        });
        Schema::dropIfExists('kurikulum');
    }
};
