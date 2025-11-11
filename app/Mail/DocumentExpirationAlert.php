<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DocumentExpirationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public string $compagnieName;
    public Collection $documents;

    public function __construct(string $compagnieName, Collection $documents)
    {
        $this->compagnieName = $compagnieName;
        $this->documents = $documents;
    }

    public function build(): self
    {
        $subject = 'Documents véhicules à renouveler (' . $this->documents->count() . ')';

        return $this->subject($subject)
            ->view('emails.document-expiration-alert')
            ->with([
                'compagnieName' => $this->compagnieName,
                'documents' => $this->documents,
            ]);
    }
}
