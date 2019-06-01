<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VoitureAccessoires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voiture_accessoires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('voiture_id');
            $table->unsignedInteger('accessoire_id');
            $table->unsignedInteger('quantité');
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
        Schema::dropIfExists('voiture_accessoires');
    }
}
