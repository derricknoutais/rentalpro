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
            $table->unsignedInteger('contractable_id');
            $table->string('contractable_type');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('compagnie_id');
            $table->unsignedInteger('offre_id')->nullable();
            $table->string('numéro')->nullable();

            $table->dateTime('du')->nullable();
            $table->dateTime('au')->nullable();
            $table->dateTime('real_check_out')->nullable();

            $table->double('prix_journalier');
            $table->integer('nombre_jours')->nullable();
            $table->double('demi_journee')->nullable();
            $table->double('montant_chauffeur')->nullable();
            $table->double('total')->nullable();
            $table->double('caution')->nullable();
            $table->string('type_caution')->nullable();
            $table->string('carburant')->nullable();
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
            $table->unsignedInteger('gescash_transaction_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('contrats');
    }
}
