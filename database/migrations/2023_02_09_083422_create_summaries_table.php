<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->string('jour_semaine');
            $table->tinyInteger('jour');
            $table->tinyInteger('mois');
            $table->tinyInteger('annee');
            $table->string('immatriculation');
            // $table->string('paiement_mode');
            $table->double('paiement');
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
        Schema::dropIfExists('summaries');
    }
}
