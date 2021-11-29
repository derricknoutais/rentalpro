<?php

namespace App\Http\Livewire;

use App\Maintenance;
use Livewire\Component;

class MaintenanceCreate extends Component
{
    public $voitures, $techniciens;

    public $titre, $voiture_id, $technicien_id, $coût, $coût_pièces, $description_panne, $pannes = [];
    public function mount($voitures, $techniciens){
        $this->voitures = $voitures;
        $this->techniciens = $techniciens;
    }
    public function ajouterPanne(){
        array_push($this->pannes, ['description' => $this->description_panne]);
        $this->description_panne = '' ;
    }
    public function creerMaintenance(){
        $maintenance = Maintenance::create([
            'titre' => $this->titre,
            'compagnie_id' => 1,
            'voiture_id' => $this->voiture_id,
            'technicien_id' => $this->technicien_id,
            'coût' => $this->coût,
            'coût_pièces' => $this->coût_pièces
        ]);

        foreach( $this->pannes as &$panne) {
            $panne['compagnie_id'] = 1;
            $panne['voiture_id'] = $maintenance->voiture_id;
            $panne['etat'] = 'non-résolue';
        };

        $pannes = $maintenance->pannes()->createMany($this->pannes);
        if($pannes){
            $this->cleanVariables();
        }
    }
    private function cleanVariables(){
        $this->titre = null;
        $this->voiture_id = null;
        $this->technicien_id = null;
        $this->coût = null;
        $this->coût_pièces = null;
        $this->description_panne = null;
        $this->pannes = [];
    }


    public function render()
    {
        return view('livewire.maintenance-create');
    }
}
