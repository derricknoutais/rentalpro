<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatutToContratsTable extends Migration
{
    public function up(): void
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->string('statut')->default('en cours')->after('real_check_out');
        });

        DB::table('contrats')->whereNotNull('real_check_out')->update([
            'statut' => 'terminé',
        ]);
    }

    public function down(): void
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
}
