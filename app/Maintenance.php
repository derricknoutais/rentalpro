<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $guarded = [];

    public function voiture()
    {
        return $this->belongsTo('App\Voiture');
    }
    public function contractable()
    {
        return $this->morphTo();
    }

    public function technicien()
    {
        return $this->belongsTo('App\Technicien');
    }
    public function pannes()
    {
        return $this->hasMany('App\Panne');
    }
}
