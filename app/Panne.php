<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panne extends Model
{
    protected $guarded = [];

    public function voiture()
    {
        return $this->belongsTo('App\Voiture', 'voiture_id');
    }
    public function estRésolue(){
        if( $this->etat === 'résolue'){
            return true;
        } else {
            return false;
        }
    }
    public function estNonRésolue(){
        if( $this->etat === 'non-résolue'){
            return true;
        } else {
            return false;
        }
    }
    public function estEnMaintenance(){
        if( $this->etat === 'en-maintenance'){
            return true;
        } else {
            return false;
        }
    }
}
