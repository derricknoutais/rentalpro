<?php

namespace App\Notifications;

use App\Contrat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Contrat $contrat)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $client = optional($this->contrat->client);
        $asset = optional($this->contrat->contractable);

        return (new MailMessage)
            ->subject('Contrat proche de la restitution')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Le contrat {$this->contrat->numéro} arrive à expiration le " . optional($this->contrat->au)->format('d/m/Y H:i') . '.')
            ->line('Client : ' . trim($client->nom . ' ' . ($client->prenom ?? '')))
            ->line('Véhicule/Asset : ' . ($asset->immatriculation ?? $asset->nom ?? 'Non renseigné'))
            ->line('Durée : ' . $this->contrat->nombre_jours . ' jour(s)')
            ->action('Ouvrir le contrat', url('/contrat/' . $this->contrat->id))
            ->line('Pensez à préparer la restitution ou à prolonger si besoin.');
    }

    public function toArray($notifiable)
    {
        return [
            'contrat_id' => $this->contrat->id,
            'numéro' => $this->contrat->numéro,
            'expire_le' => optional($this->contrat->au)->toIso8601String(),
        ];
    }
}
