<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProlongationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prolongations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contrat_id');
            $table->unsignedBigInteger('contractable_id');
            $table->datetime('du');
            $table->datetime('au');
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
        Schema::dropIfExists('prolongations');
    }
}
