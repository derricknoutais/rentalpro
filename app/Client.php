<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function contrats()
    {
        return $this->hasMany('App\Contrat');
    }

    public function nombreLocations(){
        return $this->contrats->count();
    }
}
