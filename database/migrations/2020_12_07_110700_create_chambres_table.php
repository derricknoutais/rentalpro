<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChambresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('compagnie_id');
            $table->string('nom');
            $table->enum('type', ['Budget', 'VIP']);
            $table->enum('etat', ['Disponible', 'Loué', 'Maintenance'])->nullable()->default('disponible');
            $table->integer('prix_journalier');
            $table->foreign('compagnie_id')->references('id')->on('compagnies')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('chambres');
    }
}
