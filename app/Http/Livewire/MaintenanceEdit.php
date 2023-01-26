<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Maintenance;
use App\Panne;
use Illuminate\Support\Facades\DB;

class MaintenanceEdit extends Component
{
    public $voitures, $techniciens, $maintenance;

    public $titre, $voiture_id, $technicien_id, $coût = 0, $coût_pièces = 0, $created_at, $description_panne, $pannes = [], $panne_editing = null, $panne_editing_description;

    public function mount($voitures, $techniciens, $maintenance){

        $this->titre = $maintenance->titre;
        $this->created_at = $maintenance->created_at->format('Y-m-d');
        $this->voitures = $voitures;
        $this->coût = $maintenance->coût;
        $this->coût_pièces = $maintenance->coût_pièces;
        $this->panne_editing = -1;

        if(sizeof($this->voitures) > 0)
            $this->voiture_id = $maintenance->voiture_id;
        $this->techniciens = $techniciens;
        if(sizeof($this->techniciens) > 0)
            $this->technicien_id = $maintenance->technicien_id;

        $this->pannes = $maintenance->pannes->toArray();
    }

    public function editerPanne($panne_id){
        $this->panne_editing = $panne_id;
        $this->panne_editing_description = $this->pannes[$panne_id]['description'];
    }

    public function enregistrerPanne(){
        $panne = $this->pannes[$this->panne_editing];
        $panne['description'] = $this->panne_editing_description;
        if(! isset($panne['id'])){
            $this->pannes[$this->panne_editing]['description'] = $panne['description'];
        } else {
            Panne::find($panne['id'])->update(['description' => $this->panne_editing_description]);
        }

        $this->panne_editing = -1;
    }
    public function supprimerPanne($panne_id){
        $this->panne_editing = $panne_id;
        $panne = $this->pannes[$this->panne_editing];

        if(! isset($panne['id'])){
            array_splice($this->pannes, $panne_id, 1);
        } else {
            Panne::find($panne['id'])->delete();
        }

        $this->panne_editing = -1;
    }

    public function ajouterPanne(){
        array_push($this->pannes, ['description' => $this->description_panne]);
        $this->description_panne = '' ;
    }
    public function editerMaintenance(){
        DB::transaction(function () {
            $maintenance = Maintenance::find($this->maintenance->id)->update([
                'titre' => $this->titre,
                'compagnie_id' => 1,
                'voiture_id' => $this->voiture_id,
                'technicien_id' => $this->technicien_id,
                'coût' => $this->coût,
                'coût_pièces' => $this->coût_pièces,
                'created_at' => $this->created_at,
                'updated_at' => now()
            ]);
            foreach( $this->pannes as &$panne) {
                if(! isset($panne['id'])){
                    $panne['compagnie_id'] = 1;
                    $panne['voiture_id'] = $this->maintenance->voiture_id;
                    $panne['etat'] = 'non-résolue';
                    Panne::create($panne);
                } else {
                    Panne::find($panne['id'])->update($panne);
                }

            };
            // $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->compagnie->id)->first();
            // $transactionData = [
            //     'transaction_date' => $maintenance->created_at,
            //     'tenant_id' => $apiSettings->gescash_tenant_id,
            //     'book_id' => $apiSettings->gescash_book_id,
            //     'exercise_id' => $apiSettings->gescash_exercise_id,
            //     'attachment' => 'https://rentalpro.azimuts.ga/maintenance/' . $maintenance->id,
            //     'entries' => [
            //         // Client Entry Debit
            //         [
            //             'account_id' => $apiSettings->gescash_maintenance_account_id,
            //             'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom ,
            //             'debit' => $maintenance->coût + $maintenance->coût_pièces,
            //             'credit' => NULL,
            //             'created_at' => $maintenance->created_at,
            //             'updated_at' => now()
            //         ],
            //         // Service Entry Credit
            //         [
            //             'account_id' => $apiSettings->gescash_cash_account_id,
            //             'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom ,
            //             'credit' => $maintenance->coût + $maintenance->coût_pièces,
            //             'debit' => NULL,
            //             'created_at' => $maintenance->created_at,
            //             'updated_at' => now()
            //         ]
            //     ]
            // ];
            // $sent = Http::post( env('GESCASH_BASE_URL') . '/api/v1/transaction', $transactionData);
            // $maintenance->update([
            //     'gescash_transaction_id' => $sent->json()['id'],
            // ]);
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
        return view('livewire.maintenance-edit');
    }
}
