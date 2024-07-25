<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaiementCréé extends Mailable
{
    use Queueable, SerializesModels;

    public $contrat, $paiement;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contrat, $paiement)
    {
        $this->contrat = $contrat;
        $this->paiement = $paiement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.paiement_créé', []);
    }
}
