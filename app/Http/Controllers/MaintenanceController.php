<?php

namespace App\Http\Controllers;

use App\Panne;
use App\Voiture;
use App\ApiSetting;
use App\Technicien;
use App\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maintenances = Maintenance::with(['voiture', 'technicien','pannes'])->get();
        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voitures = Voiture::all();
        $techniciens = Technicien::all();
        return view('maintenances.create', compact('voitures', 'techniciens'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::transaction(function() use ( $request ){

            // Crée une nouvelle maintenance
            $maintenance = Maintenance::create([
                'titre' => $request->titre,
                'voiture_id' => $request->voiture,
                'technicien_id' => $request->technicien,
                'compagnie_id' => Auth::user()->compagnie_id
            ]);

            // Attache les pannes sélectionnées a la maintenance créée
            for($i = 0; $i < sizeof($request->panne); $i++){
                Panne::find( $request->panne[$i])->update([
                    'voiture_id' => $request->voiture,
                    'maintenance_id' => $maintenance->id,
                    'etat' => 'en-maintenance'
                ]);
            }

            // Change l'état du véhicule en maintenance
            Voiture::find($request->voiture)->etat('maintenance');

        });
        return redirect()->back();


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function show(Maintenance $maintenance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Maintenance $maintenance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maintenance $maintenance)
    {
        //
    }
    public function envoyerMaintenanceGescash(Maintenance $maintenance){
        $done = DB::transaction(function () use ($maintenance){
            $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->id)->first();
            $transactionData = [
                'transaction_date' => $maintenance->created_at,
                'tenant_id' => $apiSettings->gescash_tenant_id,
                'book_id' => $apiSettings->gescash_book_id,
                'exercise_id' => $apiSettings->gescash_exercise_id,
                'attachment' => 'https://rentalpro.azimuts.ga/maintenance/' . $maintenance->id,
                'entries' => [
                    // Client Entry Debit
                    [
                        'account_id' => $apiSettings->gescash_maintenance_account_id,
                        'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom ,
                        'debit' => $maintenance->coût + $maintenance->coût_pièces,
                        'credit' => NULL,
                        'created_at' => $maintenance->created_at,
                        'updated_at' => now()
                    ],
                    // Service Entry Credit
                    [
                        'account_id' => $apiSettings->gescash_cash_account_id,
                        'label' => 'Maintenance sur ' . $maintenance->voiture->immatriculation . ' pour ' . $maintenance->titre . ' par ' . $maintenance->technicien->nom ,
                        'credit' => $maintenance->coût + $maintenance->coût_pièces,
                        'debit' => NULL,
                        'created_at' => $maintenance->created_at,
                        'updated_at' => now()
                    ]
                ]
            ];
            $sent = Http::post( env('GESCASH_BASE_URL') . '/api/v1/transaction', $transactionData);
            if($sent->status() == 201){
                $maintenance->update([
                    'gescash_transaction_id' => $sent->json()['id'],
                ]);
                return true;
            }
        });
        if($done){

            return redirect()->back();
        }
    }
}
