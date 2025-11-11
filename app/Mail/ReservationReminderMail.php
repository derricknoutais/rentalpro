<?php

namespace App\Mail;

use App\Compagnie;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ReservationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Compagnie $compagnie;
    public Collection $reservations;

    public function __construct(Compagnie $compagnie, Collection $reservations)
    {
        $this->compagnie = $compagnie;
        $this->reservations = $reservations;
    }

    public function build(): self
    {
        $subject = 'Réservations à venir (' . $this->reservations->count() . ')';

        return $this->subject($subject)
            ->view('emails.reservation-reminder')
            ->with([
                'compagnie' => $this->compagnie,
                'reservations' => $this->reservations,
            ]);
    }
}
