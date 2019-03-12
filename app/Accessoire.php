<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accessoire extends Model
{
    public function voitures()
    {
        return $this->belongsToMany('App\Voiture', 'voiture_documents', 'accessoire_id', 'voiture_id');
    }
}
