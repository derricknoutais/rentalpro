<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pannes', function (Blueprint $table) {
            $table->dropForeign(['maintenance_id']);
        });

        Schema::table('pannes', function (Blueprint $table) {
            $table->foreign('maintenance_id')
                ->references('id')
                ->on('maintenances')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pannes', function (Blueprint $table) {
            $table->dropForeign(['maintenance_id']);
        });

        Schema::table('pannes', function (Blueprint $table) {
            $table->foreign('maintenance_id')
                ->references('id')
                ->on('maintenances')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};
