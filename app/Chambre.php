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
        return Contrat::where(['contractable_id' => $this->id])->with('client', 'contractable')->first();
    }
}
