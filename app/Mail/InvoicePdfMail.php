<?php

namespace App\Mail;

use App\Contrat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class InvoicePdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contrat $contrat;
    public string $pdfBinary;

    public function __construct(Contrat $contrat, string $pdfBinary)
    {
        $this->contrat = $contrat;
        $this->pdfBinary = $pdfBinary;
    }

    public function build(): self
    {
        $filename = 'facture-' . Str::slug($this->contrat->numéro) . '.pdf';

        return $this->subject('Facture ' . $this->contrat->numéro)
            ->view('emails.invoice-pdf')
            ->with(['contrat' => $this->contrat])
            ->attachData($this->pdfBinary, $filename, ['mime' => 'application/pdf']);
    }
}
