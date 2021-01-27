<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookings\Traits\Bookable;

class Voiture extends Model
{
    use HasFactory;
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
        return $this->morphMany('App\Contrat', 'contractable');
        // ->orderBy('id', 'desc');
    }

    public function pannes()
    {
        return $this->hasMany('App\Panne');
    }

    public function maintenances()
    {
        return $this->hasMany('App\Maintenance');
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
    public function pannesActuelles(){
        return Panne::where(['etat' => 'non-résolue' ,'voiture_id' => $this->id])->orWhere(['etat' => 'en-maintenance', 'voiture_id' => $this->id])->get();
    }

    public function pannesNonResolues(){
        return Panne::where(['etat' => 'non-résolue' ,'voiture_id' => $this->id])->get();
    }

    public function pannesResolues(){
        return Panne::where(['etat' => 'résolue' ,'voiture_id' => $this->id])->get();
    }

    public function pannesEnMaintenance(){
        return Panne::where(['etat' => 'en-maintenance' ,'voiture_id' => $this->id])->get();
    }

}
