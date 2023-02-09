<?php

namespace App\Jobs;

use App\Paiement;
use App\Models\Summary;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class SummarizeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $paiements = Paiement::all();

        foreach($paiements as $paiement){

            $summary = Summary::create([
                'jour_semaine' => $paiement->created_at->format('d'),
                'jour' => $paiement->created_at->format('D') ,
                'mois' => $paiement->created_at->format('m'),
                'annee' => $paiement->created_at->format('Y'),
                'immatriculation' => $paiement->contrat->voiture->immatriculation,
                'paiement' => $paiement->montant
            ]);
            if($summary)
                Log::info('Summary Created');
        }
    }
}
