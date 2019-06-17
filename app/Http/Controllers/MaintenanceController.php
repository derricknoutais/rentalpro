<?php

namespace App\Http\Controllers;

use App\Maintenance;
use App\Panne;
use App\Voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        //
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
}
