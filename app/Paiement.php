<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public function contrat()
    {
        return $this->belongsTo('App\Contrat');
    }
}
