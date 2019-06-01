<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechniciensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techniciens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('compagnie_id');
            $table->string('nom');
            $table->timestamps();

            $table->foreign('compagnie_id')->references('id')->on('compagnies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('techniciens');
    }
}
