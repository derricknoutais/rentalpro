<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'du' => 'datetime',
        'au' => 'datetime',
        'converted_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_color',
    ];

    public const STATUS_EN_ATTENTE = 'en_attente';
    public const STATUS_CONFIRME = 'confirmee';
    public const STATUS_EN_COURS = 'en_cours';
    public const STATUS_ANNULEE = 'annulee';
    public const STATUS_CONVERTIE = 'convertie';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Reservation $reservation) {
            $reservation->compagnie_id = Auth::user()->compagnie->id;
            if (!$reservation->statut) {
                $reservation->statut = self::STATUS_EN_ATTENTE;
            }
        });
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_EN_ATTENTE => 'En attente',
            self::STATUS_CONFIRME => 'Confirmée',
            self::STATUS_EN_COURS => 'En cours',
            self::STATUS_ANNULEE => 'Annulée',
            self::STATUS_CONVERTIE => 'Convertie',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function contractable()
    {
        return $this->morphTo();
    }

    public function paiements()
    {
        return $this->morphMany(Paiement::class, 'payable');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->statut] ?? ucfirst(str_replace('_', ' ', $this->statut));
    }

    public function getStatusColorAttribute(): string
    {
        return [
            self::STATUS_EN_ATTENTE => '#cbd5f5',
            self::STATUS_CONFIRME => '#22c55e',
            self::STATUS_EN_COURS => '#2563eb',
            self::STATUS_ANNULEE => '#dc2626',
            self::STATUS_CONVERTIE => '#7c3aed',
        ][$this->statut] ?? '#94a3b8';
    }
}
