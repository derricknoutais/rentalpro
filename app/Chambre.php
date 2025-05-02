<?php

namespace App;

use App\Contrat;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    protected $guarded = [];
    // RELATIONSHIPS
    public function contrats()
    {
        return $this->morphMany('App\Contrat', 'contractable');
    }
    public function paiements()
    {
        return $this->hasManyThrough(Paiement::class, Contrat::class, 'contractable_id', 'contrat_id');
    }
    public function pannes()
    {
        return $this->hasMany('App\Panne');
    }
    public function maintenances()
    {
        return $this->morphMany('App\Maintenance', 'contractable');
    }

    public function contratEnCours()
    {
        if ($this->etat === 'Loué') {
            return Contrat::where(['contractable_id' => $this->id, 'contractable_type' => 'App\\Chambre'])
                ->with('client', 'contractable')
                ->latest()
                ->first();
        }
    }
    public function nom()
    {
        return $this->nom;
    }
}
