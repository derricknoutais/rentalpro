<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompagnieIdToPaiementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->unsignedInteger('compagnie_id')->nullable();
            $table->foreign('compagnie_id')->references('id')->on('compagnies')->onDelete('cascade')->onUpdate('cascade');
            $table->index(['compagnie_id']);
            $table->index(['created_at']);
            $table->index(['updated_at']);
            $table->index(['deleted_at']);
            $table->index(['payable_id']);
            $table->index(['payable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['compagnie_id']);
            $table->dropIndex(['compagnie_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['payable_id']);
            $table->dropIndex(['payable_type']);
        });
    }
}
