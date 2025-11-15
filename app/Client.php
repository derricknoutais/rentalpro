<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function compagnie()
    {
        return $this->belongsTo('App\Compagnie');
    }

    public function contrats()
    {
        return $this->hasMany('App\Contrat');
    }

    public function nombreLocations()
    {
        return $this->contrats->count();
    }
    public function chiffreAffaire()
    {
        return $this->contrats->sum('total');
    }
    public function paiementsPercus()
    {
        return Paiement::where('payable_type', 'App\\Contrat')->whereIn('payable_id', $this->contrats->pluck('id'))->sum('montant');
    }
    public function solde()
    {
        return $this->chiffreAffaire() - $this->paiementsPercus();
    }


    public function troisDerniersContrats()
    {
        return Contrat::where('client_id', $this->id)->take(3)->latest()->get();
    }

    public function nom()
    {
        return $this->nom . ' ' . $this->prenom;
    }
    /**
     * Get the user associated with the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
