<?php

namespace App;

use App\Mail\PaiementCréé;
use App\Scopes\PaiementScope;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Paiement extends Model
{
    use SoftDeletes, LogsActivity;

    protected $guarded = [];
    protected static $logUnguarded = true;

    protected $casts = [
        'created_at' => 'datetime:d-M-Y h:i',
    ];

    public static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new PaiementScope());

        static::created(function (Paiement $paiement) {
            Metric::insere($paiement);
            Mail::to('noutaiaugustin@gmail.com')
                ->cc('derricknoutais@gmail.com')
                ->send(new PaiementCréé(($contrat = $paiement->payable), $paiement));
        });

        static::updated(function (Paiement $paiement) {
            Paiement::decrementMetric(new Paiement($paiement->oldAttributes));
            Paiement::incrementMetric($paiement);
        });
        static::deleted(function (Paiement $paiement) {
            Paiement::decrementMetric($paiement);
        });
    }
    public static function incrementMetric($paiement)
    {
        $array_checksums = Metric::generateChecksumsFrom($paiement->created_at);
        foreach ($array_checksums as $checksum) {
            $reporting = Metric::where('checksum', $checksum)->first();
            $reporting->increment('paiements_percus', $paiement->montant);
        }
    }
    public static function decrementMetric($paiement)
    {
        $array_checksums = Metric::generateChecksumsFrom($paiement->created_at);
        foreach ($array_checksums as $checksum) {
            $reporting = Metric::where('checksum', $checksum)->first();
            $reporting->decrement('paiements_percus', $paiement->montant);
        }
    }
    // public function voiture(){
    //     return $this->contrat->contractable;
    // }
    // public function contrat(){
    //     return $this->belongsTo('App\Contrat');
    // }
    public function payable()
    {
        return $this->morphTo();
    }
}
