<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compagnie extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function maintenances(){
        return $this->hasMany('App\Maintenance');
    }

    public function clients(){
        return $this->hasMany('App\Client');
    }
    public function isHotel(){
        if($this->type === 'hôtel')
            return true;
        return false;
    }
    public function isVehicules(){
        if($this->type === 'véhicules')
            return true;

        return false;
    }
    public function contrats()
    {
        return $this->hasMany('App\Contrat');
    }
    public function offres()
    {
        return $this->hasMany('App\Offre');
    }
    public function contractables(){
        return $this->isVehicules() ? $this->hasMany('App\Voiture') : $this->hasMany('App\Chambre');
    }
}
