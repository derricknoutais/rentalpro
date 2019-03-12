<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    protected $guarded = [];
    protected $dates = ['check_out', 'check_in', 'created_at', 'updated_at'];
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function voiture()
    {
        return $this->belongsTo('App\Voiture');
    }

    // Methods
    protected static function numéro(){
        $nombre_contrats = Contrat::count() + 1;
        if($nombre_contrats < 10){
            $numéro_contrat = '00' . $nombre_contrats;
        } else if( $nombre_contrats >= 10 && $nombre_contrats < 100 ){
            $numéro_contrat = '0' . $nombre_contrats;
        } else {
            $numéro_contrat = $nombre_contrats;
        }
            
        return $nouveau_numéro = 'CL' . $numéro_contrat . '/' . date('m') . '/' . date('Y');
    }
}
