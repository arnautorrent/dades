<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCastellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('castells', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('abreviatura', 45)->unique();
            $table->string('nom', 100)->unique();
            $table->bigInteger('punts_descarregat')->nullable();
            $table->bigInteger('punts_carregat')->nullable();
            $table->string('estrena_descarregat', 12)->nullable();
            $table->string('estrena_placa', 12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('castells');
    }
}
