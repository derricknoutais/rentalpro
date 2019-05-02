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
}
