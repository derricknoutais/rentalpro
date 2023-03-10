<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenancesTable extends Migration
{

    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('compagnie_id');
            $table->unsignedInteger('voiture_id');
            $table->unsignedInteger('contractable_id');
            $table->string('contractable_type');
            $table->string('titre')->nullable();
            $table-> unsignedInteger('technicien_id')->nullable();
            $table->double('coût')->nullable();
            $table->double('coût_pièces')->nullable();
            $table->unsignedBigInteger('gescash_transaction_id')->nullable();

            $table->foreign('voiture_id')->references('id')->on('voitures')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('contractable_id')->references('id')->on('contractables')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('technicien_id')->references('id')->on('techniciens')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('maintenances');
    }
}
