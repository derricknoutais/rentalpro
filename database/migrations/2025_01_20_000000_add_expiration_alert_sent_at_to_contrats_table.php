<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpirationAlertSentAtToContratsTable extends Migration
{
    public function up()
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->timestamp('expiration_alert_sent_at')->nullable()->after('real_check_out');
        });
    }

    public function down()
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->dropColumn('expiration_alert_sent_at');
        });
    }
}
