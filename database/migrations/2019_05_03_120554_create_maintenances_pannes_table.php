<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintenancesPannesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenances_pannes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('maintenance_id');
            $table->unsignedInteger('panne_id');
            $table->timestamps();
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
        Schema::dropIfExists('maintenances_pannes');
    }
}
