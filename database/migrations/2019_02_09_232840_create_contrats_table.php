<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('voiture_id');
            $table->unsignedInteger('client_id');
            $table->string('numéro');
            $table->dateTime('check_out')->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('real_check_in')->nullable();
            $table->double('prix_journalier');
            $table->integer('nombre_jours');
            $table->double('total');
            $table->double('caution');
            $table->string('etat_accessoires')->nullable();
            $table->string('etat_documents')->nullable();
            $table->string('etat_accessoires_au_retour')->nullable();
            $table->string('etat_documents_au_retour')->nullable();
            $table->string('lien_photo_avant')->nullable();
            $table->string('lien_photo_arriere')->nullable();
            $table->string('lien_photo_droit')->nullable();
            $table->string('lien_photo_gauche')->nullable();
            $table->unsignedInteger('cashier_facture_id')->nullable();
            $table->unsignedInteger('prolongation_id')->nullable();
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
        Schema::dropIfExists('contrats');
    }
}
