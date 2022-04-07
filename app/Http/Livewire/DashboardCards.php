<?php

namespace App\Http\Livewire;

use App\Contrat;
use App\Voiture;
use App\Paiement;
use Carbon\Carbon;
use Livewire\Component;

class DashboardCards extends Component
{
    public $voitures;
    public $filtres1 = [
        'voiture_selectionnee' => 1,
        'date_du' => NULL,
        'date_au' => NULL,
        'taux_recouvrement' => [
            'periode' => null,
            'periode_comparaison' => null
        ],
        'paiements' => [
            'periode' => null,
            'periode_comparaison' => null
        ],
        'jours_location' => [
            'periode' => null,
            'periode_comparaison' => null
        ]
    ];
    public $filtres2 = [
        'voiture_selectionnee' => NULL,
        'date_du' => NULL,
        'date_au' => NULL,
        'taux_recouvrement' => [
            'periode' => null,
            'periode_comparaison' => null
        ],
        'paiements' => [
            'periode' => null,
            'periode_comparaison' => null
        ],
        'jours_location' => [
            'periode' => null,
            'periode_comparaison' => null
        ]
    ];

    public $voiture_selectionnee, $date_du, $date_au;
    public $voiture_comparaison, $date_du_comparaison, $date_au_comparaison;

    public function mount(){
        $this->voitures = Voiture::all();
        $date = Carbon::now();
        $this->filtres1['date_au'] = $date->format('Y-m-d');
        $this->filtres1['date_du'] = $date->copy()->startOfYear()->format('Y-m-d');
        // $this->filtrer();
    }
    public function fi(&$data){
        $voiture = null;
        if(isset($data['voiture_selectionnee'])){
            $voiture = Voiture::find($data['voiture_selectionnee'])->loadMissing('paiements', 'contrats');
            $paiements = $voiture->paiements;
            $contrats = $voiture->contrats;
        } else {
            $paiements = Paiement::query();
            $contrats = Contrat::query();
        }
        if($data['date_du']){
            $paiements = $paiements->where('created_at', '>=', $data['date_du']);
            $contrats = $contrats->where('created_at', '>=', $data['date_du']);
        }
        if($data['date_au']){
            $paiements = $paiements->where('created_at', '<=', $data['date_au']);
            $contrats = $contrats->where('created_at', '>=', $data['date_du']);
        }
        $data['paiements']['periode'] = $paiements->sum('montant');
        $data['jours_location']['periode'] = $contrats->sum('nombre_jours');
        if($contrats->sum('total')){
            $data['taux_recouvrement']['periode'] = ( $paiements->sum('montant') / $contrats->sum('total') ) * 100;
        }
    }
    public function filtrer( $filtre ){
        $this->fi($this->$filtre);
    }

    public function render()
    {
        return view('livewire.dashboard-cards');
    }
}
