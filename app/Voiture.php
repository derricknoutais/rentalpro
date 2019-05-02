<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    protected $guarded = [];
    public function documents()
    {
        return $this->belongsToMany('App\Document', 'voiture_documents', 'voiture_id', 'document_id')->withPivot('date_expiration');
    }
    public function accessoires()
    {
        return $this->belongsToMany('App\Accessoire', 'voiture_accessoires', 'voiture_id', 'accessoire_id')->withPivot('quantité');
    }

    public function contrats()
    {
        return $this->hasMany('App\Contrat');
    }

    public function pannes()
    {
        return $this->hasMany('App\Panne');
    }

    public static function compteVoitures($etat){
        return Voiture::where('etat', $etat)->count();
    }
    public static function moinsPerformante(){
        
    }
    public function etat($etat){
        $this->update([
            'etat' => $etat
        ]);
    }
}
