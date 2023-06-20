<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Paiement extends Model
{
    use SoftDeletes, LogsActivity;

    protected $guarded = [];
    protected static $logUnguarded = true;

    public static function boot(){
        parent::boot();

        static::created(function(Paiement $paiement){
            Metric::insere($paiement);
        });

        static::updated(function (Paiement $paiement){
            Paiement::decrementMetric(new Paiement($paiement->oldAttributes));
            Paiement::incrementMetric($paiement);
        });
        static::deleted(function(Paiement $paiement){
            Paiement::decrementMetric($paiement);
        });

    }
    public static function incrementMetric($paiement){
        $array_checksums = Metric::generateChecksumsFrom($paiement->created_at);
        foreach ($array_checksums as  $checksum) {
            $reporting = Metric::where('checksum', $checksum)->first();
            $reporting->increment(
                'paiements_percus' , $paiement->montant
            );
        }
    }
    public static function decrementMetric($paiement){
        $array_checksums = Metric::generateChecksumsFrom($paiement->created_at);
            foreach ($array_checksums as  $checksum) {
                $reporting = Metric::where('checksum', $checksum)->first();
                $reporting->decrement(
                    'paiements_percus' , $paiement->montant
                );
            }
    }
    // public function voiture(){
    //     return $this->contrat->contractable;
    // }
    // public function contrat(){
    //     return $this->belongsTo('App\Contrat');
    // }
    public function payable(){
        return $this->morphTo();
    }
}
