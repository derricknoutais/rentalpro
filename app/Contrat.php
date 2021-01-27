<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Contrat extends Model
{
    use SoftDeletes, LogsActivity, HasFactory;

    protected $guarded = [];
    protected static $logAttributes = '*';
    protected $dates = ['au', 'du'];

    // Relationships

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function contractable()
    {
        return $this->morphTo();
    }

    public function compagnie()
    {
        return $this->belongsTo('App\Compagnie');
    }
    public function paiements()
    {
        return $this->hasMany('App\Paiement');
    }
    public function total(){
        return $this->nombre_jours * $this->prix_journalier;
    }
    public function payé(){
        $total_payé = 0;
        foreach ($this->paiements as $paiement ) {
            $total_payé += $paiement->montant;
        }
        return $total_payé;
    }
    public function solde(){
        return $this->total() - $this->payé();
    }


    // Methods
    // protected static function numéro(){
    //     $numero_contrat = Contrat::count() + 1;
    //     if($numero_contrat < 10){
    //         $numéro = '00' . $numero_contrat;
    //     } else if( $numero_contrat >= 10 && $numero_contrat < 100 ){
    //         $numéro = '0' . $numero_contrat;
    //     } else {
    //         $numéro = $numero_contrat;
    //     }
    //     return $nouveau_numéro = 'CL' . $numéro . '/' . date('m') . '/' . date('Y');
    // }

    public static function numéro(){

        $numéro = '';
        $numéro =  DB::transaction(function () use ($numéro) {
            $contrats_du_mois = Contrat::where('compagnie_id', Auth::user()->compagnie->id)->whereMonth( 'created_at', Carbon::now()->month )->whereYear( 'created_at', Carbon::now()->year )->get();

            // Si nous sommes le premier du mois ...

            if( Carbon::today()->isSameDay(new Carbon('first day of this month')) ) {

                // ... & aucun contrat n'est établi

                if( sizeof( $contrats_du_mois )  === 0){

                    // reinitialise la numérotation du contrat a 1

                    Auth::user()->compagnie->update([
                        'numero_contrat' => 1
                    ]);
                }
            };
            // Récupérer le numero de contrat actuel
            $numero_contrat = Auth::user()->compagnie->numero_contrat;

            if($numero_contrat < 10){
                $numéro = '00' . $numero_contrat;
            } else if( $numero_contrat >= 10 && $numero_contrat < 100 ){
                $numéro = '0' . $numero_contrat;
            } else {
                $numéro = $numero_contrat;
            }
            Auth::user()->compagnie->increment('numero_contrat');

            return $numéro;
        });

        return $nouveau_numéro = 'CL' . $numéro . '/' . date('m') . '/' . date('Y');
    }
}
