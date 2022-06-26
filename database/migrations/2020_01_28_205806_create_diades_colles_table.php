<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiadesCollesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diades_colles', function (Blueprint $table) {
            $table->bigInteger('id_diada')->unsigned();
            $table->bigInteger('id_colla')->unsigned();
            $table->bigIncrements('id');

            $table->foreign('id_diada')->references('id')->on('diades');
            $table->foreign('id_colla')->references('id')->on('colles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diades_colles');
    }
}
