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
            $table->smallInteger('annee');
            $table->string('marque');
            $table->string('type');
            $table->enum('etat', ['disponible', 'loué', 'maintenance']);
            $table->double('prix');
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