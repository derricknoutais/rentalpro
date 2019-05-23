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
            $table->unsignedInteger('voiture_id');
            $table->unsignedInteger('maintenance_id')->nullable();
            $table->string('description');
            $table->enum('etat', ['non-résolue', 'résolue', 'en-maintenance'])->default('non-résolue');
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
        Schema::dropIfExists('pannes');
    }
}
