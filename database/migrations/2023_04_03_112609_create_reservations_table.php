<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('compagnie_id');

            $table->unsignedInteger('contractable_id');
            $table->string('contractable_type');

            $table->unsignedInteger('client_id')->nullable();
            $table->dateTime('du')->nullable();
            $table->dateTime('au')->nullable();


            $table->double('demi_journee')->nullable();
            $table->double('montant_chauffeur')->nullable();

            $table->double('caution')->nullable();
            $table->text('note')->nullable();

            // Valeurs Calculees
            $table->integer('nombre_jours')->nullable();
            $table->double('total')->nullable();


            $table->timestamps();
            $table->softDeletes();

            $table->foreign('compagnie_id')->references('id')->on('compagnies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
