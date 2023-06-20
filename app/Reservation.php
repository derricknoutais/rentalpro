<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    protected $guarded = [];
    protected $dates = ['du', 'au'];
    public static function boot(){
        parent::boot();
        static::creating(function(Reservation $reservation){
            $reservation->compagnie_id = Auth::user()->compagnie->id;
        });
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function contractable()
    {
        return $this->morphTo();
    }
    public function paiements()
    {
        return $this->morphMany(Paiement::class, 'payable');
    }
    use HasFactory;
}
