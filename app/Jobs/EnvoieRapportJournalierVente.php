<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\RapportJournalierVente;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class EnvoieRapportJournalierVente implements ShouldQueue
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
        Log::info('Envoi du rapport journalier de vente');
        Mail::to('derricknoutais@gmail.com')->cc('noutaiaugustin@gmail.com')->bcc('servicesazimuts@gmail.com')->send(new RapportJournalierVente());
        Log::info('Rapport journalier de vente envoyé avec succès');
    }
}
