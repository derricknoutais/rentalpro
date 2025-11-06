<?php

namespace App;

use App\Chambre;
use App\Voiture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contractable extends Model
{
    use HasFactory;
    public static function query()
    {
        return Auth::user()->compagnie->isVehicules() ? Voiture::query() : Chambre::query();
    }
    public static function type()
    {
        return Auth::user()->compagnie->isVehicules() ? 'Voiture' : 'Chambre';
    }
    public function nom()
    {
        if ($this->type() == 'Voiture') {
            return $this->immatriculation;
        } else {
            return $this->nom;
        }
    }
}
