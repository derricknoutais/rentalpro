<?php

namespace App\Http\Controllers;

use App\Offre;
use App\Client;
use App\Paiement;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::with('contractable')->get();
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $compagnie = Auth::user()->compagnie;
        $contrats = $compagnie->contrats;
        $offres = Offre::all();
        $contractables = $compagnie->contractables;
        $clients = $compagnie->clients;
        return view('reservations.create', compact('contractables', 'clients', 'compagnie', 'offres', 'contrats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contractable_type = Auth::user()->compagnie->isHotel() ? 'App\\Hotel' : 'App\\Voiture';
        $reservation = Reservation::create([
            'contractable_id' => $request->contractable,
            'contractable_type' => $contractable_type,
            'client_id' => $request->client_id,
            'du' => $request->du,
            'au' => $request->au,
            'demi_journee' => $request->demi_journee,
            'montant_chauffeur' => $request->montant_chauffeur,
            'caution' => $request->caution,
            'note' => $request->note,
        ]);

        if ($reservation && $request->paiement) {
            $paiement = Paiement::create([
                'payable_id' => $reservation->id,
                'payable_type' => 'App\\Reservation',
                'montant' => $request->paiement,
            ]);
        }

        return redirect('/reservations');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        $reservation->loadMissing('client', 'contractable');
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
