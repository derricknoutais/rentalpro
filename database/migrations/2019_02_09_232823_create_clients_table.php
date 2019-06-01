<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('compagnie_id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('adresse')->nullable();
            $table->string('mail')->nullable();
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->string('numero_permis')->nullable();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->string('phone3')->nullable();
            $table->string('permis')->nullable();
            $table->unsignedInteger('cashier_id')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
