<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voitures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('immatriculation');
            $table->string('chassis')->nullable();
            $table->smallInteger('annee')->nullable();
            $table->string('marque')->nullable();
            $table->string('type')->nullable();
            $table->enum('etat', ['disponible', 'loué', 'maintenance'])->nullable();
            $table->double('prix')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voitures');
    }

}