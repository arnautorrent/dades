<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiadesCastellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diades_castells', function (Blueprint $table) {
            $table->bigInteger('id_diada')->unsigned();
            $table->string('castell', 45);
            $table->string('resultat', 2);
            $table->bigInteger('ronda');
            $table->bigIncrements('id');

            $table->foreign('id_diada')->references('id')->on('diades');
            $table->foreign('castell')->references('abreviatura')->on('castells');
            $table->foreign('resultat')->references('abreviatura')->on('resultats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diades_castells');
    }
}
