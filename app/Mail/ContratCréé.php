<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContratCréé extends Mailable
{
    use Queueable, SerializesModels;

    public $contrat;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contrat)
    {
        $this->contrat = $contrat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contrat_créé', compact($this->contrat));
    }
}
