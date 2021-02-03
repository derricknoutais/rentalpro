<?php

namespace App;

use App\Contrat;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    protected $guarded = [];
    public function contrats()
    {
        return $this->morphMany('App\Contrat', 'contractable');
    }
    public function contratEnCours(){
        if($this->etat === 'Loué'){
            return Contrat::where(['contractable_id' => $this->id, 'contractable_type' => 'App\\Chambre'])->with('client', 'contractable')->latest()->first();
        }
    }
}
