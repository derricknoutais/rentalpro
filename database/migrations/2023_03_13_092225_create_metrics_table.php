<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();

            $table->string('checksum')->unique()->index();
            $table->string('type')->nullable();

            $table->smallInteger('annee')->nullable();

            $table->tinyInteger('trimestre')->nullable();
            $table->string('trimestre_label')->nullable();

            $table->tinyInteger('mois')->nullable();
            $table->string('mois_label')->nullable();

            $table->tinyInteger('semaine')->nullable();
            $table->string('semaine_label')->nullable();

            $table->tinyInteger('jour')->nullable();
            $table->tinyInteger('jour_semaine_code')->nullable();
            $table->string('jour_semaine_court')->nullable();
            $table->string('jour_semaine_long')->nullable();

            $table->double('chiffre_affaires')->default(0)->nullable();
            $table->double('depenses')->default(0)->nullable();
            $table->double('benefice')->default(0)->nullable();
            $table->double('cout_main_oeuvre')->default(0)->nullable();
            $table->double('cout_pieces')->default(0)->nullable();
            $table->double('paiements_percus')->default(0)->nullable();
            $table->double('acompte')->default(0)->nullable();

            $table->smallInteger('nombre_locations')->default(0)->nullable();
            $table->smallInteger('nombre_jours')->default(0)->nullable();
            $table->smallInteger('nombre_maintenances')->default(0)->nullable();
            $table->smallInteger('nombre_pannes')->default(0)->nullable();

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
        Schema::dropIfExists('reportings');
    }
}
