<?php

namespace App\Http\Livewire;

use App\Contractable;
use App\Contrat;
use App\Voiture;
use App\Paiement;
use Carbon\Carbon;
use App\Maintenance;
use Livewire\Component;

class DashboardCards extends Component
{
    public $contractables;
    public $filtres1 = [
        'contractable_selectionnee' => '*',
        'date_du' => NULL,
        'date_au' => NULL,
        'taux_recouvrement' => [
            'periode' => null,
        ],
        'maintenances' => [
            'periode' => null,
        ],
        'paiements' => [
            'periode' => null,
        ],
        'jours_location' => [
            'periode' => null,
        ]
    ];
    public $filtres2 = [
        'contractable_selectionnee' => NULL,
        'date_du' => NULL,
        'date_au' => NULL,
        'taux_recouvrement' => [
            'periode' => null,
        ],
        'paiements' => [
            'periode' => null,
        ],
        'maintenances' => [
            'periode' => null,
        ],
        'jours_location' => [
            'periode' => null,
        ]
    ];

    public $contractable_selectionnee, $date_du, $date_au;
    public $contractable_comparaison, $date_du_comparaison, $date_au_comparaison;

    public function mount(){
        $this->contractables = Contractable::query()->get();
        $date = Carbon::now();
        $this->filtres1['date_au'] = $date->format('Y-m-d');
        $this->filtres1['date_du'] = $date->format('Y-m-d');
        $this->filtres1['paiements']['periode'] = Paiement::where('created_at', '>=' ,Carbon::today())->sum('montant');
        // $this->filtrer();
    }

    public function fi(&$data){
        $contractable = null;
        if(isset($data['contractable_selectionnee']) && ($data['contractable_selectionnee'] !== '*') ){
            $query = Contractable::query();
            $contractable = $query->find($data['contractable_selectionnee'])->loadMissing('paiements', 'contrats', 'maintenances');

            $paiements = $contractable->paiements;
            $contrats = $contractable->contrats;
            $maintenances = $contractable->maintenances;
        } else {
            $paiements = Paiement::query();
            $contrats = Contrat::query();
            $maintenances = Maintenance::query();
        }
        if($data['date_du']){
            $paiements = $paiements->where('created_at', '>=', Carbon::parse($data['date_du'])->startOfDay());
            $contrats = $contrats->where('created_at', '>=', Carbon::parse($data['date_du'])->startOfDay());
            $maintenances = $maintenances->where('created_at', '>=', Carbon::parse($data['date_du'])->startOfDay());
        }
        if($data['date_au']){
            $paiements = $paiements->where('created_at', '<=',  Carbon::parse( $data['date_au'])->endOfDay()  );
            $contrats = $contrats->where('created_at', '<=', Carbon::parse($data['date_au'])->endOfDay());
            $maintenances = $maintenances->where('created_at', '<=', Carbon::parse($data['date_au'])->endOfDay());

        }
        $data['paiements']['periode'] = $paiements->sum('montant');
        $data['maintenances']['periode'] = $maintenances->sum('coût') + $maintenances->sum('coût_pièces');
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
