<?php

namespace App\Http\Livewire;

use App\ApiSetting;
use App\Maintenance;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MaintenanceCreate extends Component
{
    public $voitures, $techniciens;

    public $titre, $voiture_id, $technicien_id, $coût = 0, $coût_pièces = 0, $created_at, $description_panne, $pannes = [];
    public function mount($voitures, $techniciens){
        $this->created_at = now()->format('Y-m-d');
        $this->voitures = $voitures;
        $this->voiture_id = $voitures[0]->id;
        $this->techniciens = $techniciens;
        $this->technicien_id = $techniciens[0]->id;
    }
    public function ajouterPanne(){
        array_push($this->pannes, ['description' => $this->description_panne]);
        $this->description_panne = '' ;
    }
    public function creerMaintenance(){
        DB::transaction(function () {

            $maintenance = Maintenance::create([
                'titre' => $this->titre,
                'compagnie_id' => 1,
                'voiture_id' => $this->voiture_id,
                'technicien_id' => $this->technicien_id,
                'coût' => $this->coût,
                'coût_pièces' => $this->coût_pièces,
                'created_at' => $this->created_at,
                'updated_at' => $this->created_at
            ]);

            foreach( $this->pannes as &$panne) {
                $panne['compagnie_id'] = 1;
                $panne['voiture_id'] = $maintenance->voiture_id;
                $panne['etat'] = 'non-résolue';
            };

            $pannes = $maintenance->pannes()->createMany($this->pannes);


            $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->id)->first();
            $transactionData = [
                'transaction_date' => $maintenance->created_at,
                'tenant_id' => $apiSettings->tenant_id,
                'book_id' => $apiSettings->gescash_book_id,
                'exercise_id' => $apiSettings->gescash_exercise_id,
                'attachment' => 'https://rentalpro.azimuts.ga/maintenance/' . $maintenance->id,
                'entries' => [
                    // Client Entry Debit
                    [
                        'account_id' => $apiSettings->gescash_maintenance_account_id,
                        'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom ,
                        'debit' => $maintenance->coût + $maintenance->coût_pièces,
                        'credit' => NULL
                    ],
                    // Service Entry Credit
                    [
                        'account_id' => $apiSettings->gescash_cash_account_id,
                        'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . 'par' . $maintenance->technicien->nom ,
                        'credit' => $maintenance->coût + $maintenance->coût_pièces,
                        'debit' => NULL
                    ]
                ]
            ];
            Http::post( env('GESCASH_BASE_URL') . '/api/v1/transaction', $transactionData);
            $this->cleanVariables();

        });
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
