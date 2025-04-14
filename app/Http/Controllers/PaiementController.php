<?php

namespace App\Http\Controllers;

use App\Contrat;
use App\Paiement;
use App\ApiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paiements = Paiement::with('payable', 'payable.contractable')->limit(100)->get();

        return view('paiements.index', compact('paiements'));
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
        $done = DB::transaction(function () use ($request) {
            $contrat = Contrat::find($request->contrat_id);
            if ($contrat->solde() < $request->montant) {
                flash('Le Montant Imputé est supérieur au solde. Veuillez imputer une valeur inférieure ou égale au solde.')->error();
                return redirect()->back();
            }
            if ($request->payer_avec_caution) {
                $paiement = Paiement::create([
                    'payable_id' => $request->contrat_id,
                    'payable_type' => 'App\\Contrat',
                    'montant' => $request->montant,
                    'note' => 'Retiré de la caution',
                ]);
                $contrat->update([
                    'caution' => $contrat->caution - $request->montant,
                ]);
            } else {
                $paiement = Paiement::create([
                    'payable_id' => $request->contrat_id,
                    'payable_type' => 'App\\Contrat',
                    'montant' => $request->montant,
                    'note' => $request->note,
                ]);
            }
            $apiSettings = ApiSetting::where('compagnie_id', Auth::user()->compagnie->id)->first();
            if ($paiement && $contrat->gescash_transaction_id) {
                $response = Http::post(env('GESCASH_BASE_URL') . '/api/v1/transaction/' . $contrat->gescash_transaction_id . '/entry', [
                    'entries' => [
                        // Client Entry Credit
                        [
                            'account_id' => $apiSettings->gescash_client_account_id,
                            'label' => 'Paiment Contrat ' . $contrat->numéro,
                            'credit' => $request->montant,
                            'debit' => null,
                        ],
                        // Caisse Entry Debit
                        [
                            'account_id' => $apiSettings->gescash_cash_account_id,
                            'label' => 'Paiment Contrat ' . $contrat->numéro,
                            'debit' => $request->montant,
                            'credit' => null,
                        ],
                    ],
                ]);
                if ($response->status() == 201) {
                    $updated = $paiement->update([
                        'gescash_entry_id' => $response->json()['id'],
                    ]);
                    if ($updated) {
                        return true;
                    }
                }
            }
            return true;
        });
        if ($done) {
            flash('Paiement effectué avec succès')->success();
            return redirect()->back();
        }
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
        $contrat = Contrat::find($paiement->payable_id);

        if ($contrat->solde() <= $request->montant) {
            flash('Le Montant Imputé est supérieur au solde. Veuillez imputer une valeur inférieure ou égale au solde.')->error();
            return redirect()->back();
        }
        $paiement->update([
            'montant' => $request->montant,
            'note' => $request->note,
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
        // return $paiement;
        $paiement->delete();
        // flash('Paiement supprimé avec succès')->success();
        // return redirect()->back();
    }
}
