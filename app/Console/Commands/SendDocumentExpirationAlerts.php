<?php

namespace App\Console\Commands;

use App\Mail\DocumentExpirationAlert;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendDocumentExpirationAlerts extends Command
{
    protected $signature = 'documents:alert-expiring {--days=7 : Nombre de jours avant expiration}';

    protected $description = 'Vérifie les documents véhicules et envoie un rappel avant expiration.';

    public function handle(): int
    {
        $window = max((int) $this->option('days'), 1);
        $timezone = 'Africa/Libreville';
        $start = Carbon::now($timezone)->startOfDay();
        $end = $start->copy()->addDays($window);

        $alerts = DB::table('voiture_documents as vd')
            ->join('documents as d', 'd.id', '=', 'vd.document_id')
            ->join('voitures as v', 'v.id', '=', 'vd.voiture_id')
            ->join('compagnies as c', 'c.id', '=', 'v.compagnie_id')
            ->select(['vd.voiture_id', 'vd.document_id', 'vd.date_expiration', 'v.immatriculation', 'v.marque', 'v.type', 'v.compagnie_id', 'c.nom as compagnie_nom', 'd.type as document_type'])
            ->whereNotNull('vd.date_expiration')
            ->whereBetween('vd.date_expiration', [$start->toDateString(), $end->toDateString()])
            ->orderBy('vd.date_expiration')
            ->get()
            ->groupBy('compagnie_id');

        if ($alerts->isEmpty()) {
            $this->info('Aucun document à notifier.');
            return Command::SUCCESS;
        }

        $alerts->each(function (Collection $documents, $compagnieId) use ($start) {
            if (env('APP_ENV') !== 'production') {
                // In non-production environments, send alerts only to specific emails
                $emails = collect(['derricknoutais@gmail.com']);
            } else {
                $emails = ['derricknoutais@gmail.com', 'noutaiaugustin@gmail.com'];
            }

            if ($emails->isEmpty()) {
                return;
            }

            $payload = $documents->map(function ($row) use ($start) {
                $expiration = Carbon::parse($row->date_expiration);
                $row->days_remaining = $start->diffInDays($expiration, false);
                $row->expiration_date = $expiration;
                $row->voiture_url = url('/voiture/' . $row->voiture_id);

                return $row;
            });

            Mail::to($emails->all())->send(new DocumentExpirationAlert($documents->first()->compagnie_nom ?? 'Votre Compagnie', $payload));
        });

        $this->info('Alertes documents envoyées.');

        return Command::SUCCESS;
    }
}
