<?php

namespace App\Mail;

use App\Paiement;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RapportJournalierVente extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $paiements_airtelmoney = Paiement::where('created_at', '>', Carbon::today()->subDay()->startOfDay()->addHours(18))
            ->where('created_at', '<', Carbon::today()->setTime(18, 00, 00))
            ->where('type_paiement', 'Airtel Money')
            ->load('payable', 'payable.contractable')
            ->with([
                'payable' => function ($query) {
                    $query->withoutGlobalScopes();
                },
            ])
            ->orderBy('type_paiement')
            ->get();
        $paiements_espece = Paiement::where('created_at', '>', Carbon::today()->subDay()->startOfDay()->addHours(18))
            ->where('created_at', '<', Carbon::today()->setTime(18, 00, 00))
            ->where('type_paiement', 'Espèce')
            ->load('payable', 'payable.contractable')
            ->with([
                'payable' => function ($query) {
                    $query->withoutGlobalScopes();
                },
            ])
            ->orderBy('type_paiement')
            ->get();
        // $pdf = PDF::loadView('rapports.paiement-journalier', compact('paiements_airtelmoney', 'paiements_espece'));
        return $this->view('emails.rapport-journalier-ventes', compact('paiements_airtelmoney', 'paiements_espece'));
    }
}
