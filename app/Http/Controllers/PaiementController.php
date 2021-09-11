<?php

namespace App\Http\Controllers;

use App\Paiement;
use App\Contrat;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $contrat = Contrat::find($request->contrat_id);
        dd([$request->all()]);
        if( $contrat->solde() < $request->montant  ){

            flash('Le Montant Imputé est supérieur au solde. Veuillez imputer une valeur inférieure ou égale au solde.')->error();
            return redirect()->back();
        }
        $paiement = Paiement::create([
            'contrat_id' => $request->contrat_id,
            'montant' => $request->montant,
            'note' => $request->note
        ]);
        flash('Paiement de ' . $request->montant . ' enregistré avec succès')->success();
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function show(Paiement $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paiement $paiement)
    {
        $contrat = Contrat::find($paiement->contrat_id);

        if( $contrat->solde() <= $request->montant  ){
            flash('Le Montant Imputé est supérieur au solde. Veuillez imputer une valeur inférieure ou égale au solde.')->error();
            return redirect()->back();
        }
        $paiement->update([
            'montant' => $request->montant,
            'note' => $request->note
        ]);
        flash('Paiement Modifié avec succès')->success();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        flash('Paiement supprimé avec succès')->success();
        return redirect()->back();
    }
}
