<?php

namespace App\Console\Commands;

use App\Mail\ReservationReminderMail;
use App\Reservation;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendReservationReminderEmails extends Command
{
    protected $signature = 'reservations:remind-upcoming {--window=3 : Nombre de jours à surveiller}';

    protected $description = 'Envoie un rappel quotidien pour les réservations prévues dans les trois prochains jours.';

    public function handle(): int
    {
        $window = max((int) $this->option('window'), 1);
        $timezone = 'Africa/Libreville';
        $now = Carbon::now($timezone)->startOfDay();
        $end = $now->copy()->addDays($window)->endOfDay();

        $reservations = Reservation::with(['client', 'contractable', 'compagnie'])
            ->whereNotNull('du')
            ->whereBetween('du', [$now, $end])
            ->whereNotIn('statut', [Reservation::STATUS_ANNULEE])
            ->orderBy('compagnie_id')
            ->orderBy('du')
            ->get()
            ->groupBy('compagnie_id');

        if ($reservations->isEmpty()) {
            $this->info('Aucune réservation à rappeler.');
            return Command::SUCCESS;
        }

        $reservations->each(function (Collection $group) use ($now) {
            /** @var \App\Reservation $first */
            $first = $group->first();
            if (!$first) {
                return;
            }

            $emails = User::where('compagnie_id', $first->compagnie_id)->pluck('email')->filter()->unique();
            if ($emails->isEmpty()) {
                return;
            }

            $payload = $group->map(function (Reservation $reservation) use ($now) {
                $reservation->days_until = $now->diffInDays($reservation->du, false);
                $reservation->detail_url = route('reservations.show', $reservation);
                return $reservation;
            });

            Mail::to($emails->all())->send(new ReservationReminderMail($first->compagnie, $payload));
        });

        $this->info('Rappels de réservations envoyés.');

        return Command::SUCCESS;
    }
}
