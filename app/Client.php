<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function compagnie(){
        return $this->belongsTo('App\Compagnie');
    }

    public function contrats()
    {
        return $this->hasMany('App\Contrat');
    }

    public function nombreLocations(){
        return $this->contrats->count();
    }
    public function chiffreAffaire(){
        return $this->contrats->sum('total');
    }
    public function paiementsPercus(){
        return Paiement::whereIn('contrat_id', $this->contrats->pluck('id'))->sum('montant');
    }
    public function solde(){
        return $this->chiffreAffaire() - $this->paiementsPercus();
    }


    public function troisDerniersContrats(){
        return Contrat::where('client_id', $this->id)->take(3)->latest()->get();
    }
}
