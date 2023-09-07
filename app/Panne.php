<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Panne extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function (Panne $panne) {
            $panne->compagnie_id = Auth::user()->compagnie_id;
            $panne->contractable_type = 'App\\' . Contractable::type();
        });
    }

    public function voiture()
    {
        return $this->belongsTo('App\Voiture', 'voiture_id');
    }
    public function contractable()
    {
        return $this->morphTo();
    }

    public function estRésolue()
    {
        if ($this->etat === 'résolue') {
            return true;
        } else {
            return false;
        }
    }
    public function estNonRésolue()
    {
        if ($this->etat === 'non-résolue') {
            return true;
        } else {
            return false;
        }
    }
    public function estEnMaintenance()
    {
        if ($this->etat === 'en-maintenance') {
            return true;
        } else {
            return false;
        }
    }
}
