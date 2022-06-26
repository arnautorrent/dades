<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data');
            $table->text('diada');
            $table->text('poblacio');
            $table->tinyInteger('millor_actuacio')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diades');
    }
}
