<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compagnie_id');
            $table->unsignedBigInteger('gescash_tenant_id');
            $table->unsignedBigInteger('gescash_book_id');
            $table->unsignedBigInteger('gescash_exercise_id');

            $table->unsignedBigInteger('gescash_client_account_id');
            $table->unsignedBigInteger('gescash_client_label');

            $table->unsignedBigInteger('gescash_service_account_id');
            $table->unsignedBigInteger('gescash_service_label');

            $table->unsignedBigInteger('gescash_cash_account_id');
            $table->unsignedBigInteger('gescash_cash_label');
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
        Schema::dropIfExists('api_settings');
    }
}
