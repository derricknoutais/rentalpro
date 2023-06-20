<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePannesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pannes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('compagnie_id');
            $table->unsignedInteger('contractable_id');
            $table->string('contractable_type');
            $table->unsignedInteger('maintenance_id')->nullable();
            $table->string('description');
            $table->enum('etat', ['non-résolue', 'résolue', 'en-maintenance'])->default('non-résolue');
            $table->timestamps();

            $table->foreign('compagnie_id')->references('id')->on('compagnies')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign( 'voiture_id')->references('id')->on('voitures')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign( 'maintenance_id')->references('id')->on('maintenances')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pannes');
    }
}
