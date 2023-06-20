<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{

    protected $guarded = [];
    public static function boot()
    {
        parent::boot();

        static::created(function (Maintenance $maintenance) {
            Metric::insere($maintenance);
        });

        static::updated(function (Maintenance $maintenance) {
        });
    }
    private static function incrementMetric($maintenance)
    {
        $array_checksums = Metric::generateCheckSumsFrom($maintenance->created_at);
        foreach ($array_checksums as  $checksum) {
            $reporting = Metric::where('checksum', $checksum)->first();
            $reporting->increment('nombre_maintenances');
            if ($maintenance->coût) {
                $reporting->increment('cout_main_oeuvre', $maintenance->coût);
            }
            if ($maintenance->coût_pièces) {
                $reporting->increment('cout_pieces', $maintenance->coût_pièces);
            }
        }
    }
    public function voiture()
    {
        return $this->belongsTo('App\Voiture');
    }
    public function contractable()
    {
        return $this->morphTo();
    }

    public function technicien()
    {
        return $this->belongsTo('App\Technicien');
    }
    public function pannes()
    {
        return $this->hasMany('App\Panne');
    }
}
