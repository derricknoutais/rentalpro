<?php

namespace App\Console\Commands;

use App\Contrat;
use App\Notifications\ContractExpiringNotification;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendContractExpiryAlerts extends Command
{
    protected $signature = 'contracts:alert-expiring {--hours=24 : Période (en heures) avant expiration}';

    protected $description = 'Envoie des notifications lorsque des contrats approchent de leur date de fin.';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $now = now();
        $limit = now()->addHours($hours);

        Contrat::with(['client', 'contractable'])
            ->where('statut', Contrat::STATUS_EN_COURS)
            ->whereNull('expiration_alert_sent_at')
            ->whereBetween('au', [$now, $limit])
            ->orderBy('compagnie_id')
            ->chunkById(100, function ($contrats) {
                foreach ($contrats as $contrat) {
                    $users = User::where('compagnie_id', $contrat->compagnie_id)->get();

                    if ($users->isEmpty()) {
                        continue;
                    }

                    Notification::send($users, new ContractExpiringNotification($contrat));
                    $contrat->forceFill(['expiration_alert_sent_at' => now()])->save();
                }
            });

        $this->info('Alertes d’expiration envoyées.');

        return Command::SUCCESS;
    }
}
