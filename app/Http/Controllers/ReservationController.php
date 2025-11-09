<?php

namespace App\Http\Controllers;

use App\Offre;
use App\Client;
use App\Paiement;
use App\Reservation;
use Carbon\Carbon;
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
        $compagnie = Auth::user()->compagnie;
        $clients = $compagnie->clients()->orderBy('nom')->get();
        $contractables = $compagnie->contractables()->orderBy('id')->get();
        $statusOptions = Reservation::statusOptions();

        return view('reservations.index', compact('clients', 'contractables', 'statusOptions', 'compagnie'));
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
        $data = $this->prepareReservationData($request);
        $reservation = Reservation::create($data);

        if ($reservation && $request->paiement) {
            Paiement::create([
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
        $compagnie = Auth::user()->compagnie;
        $clients = $compagnie->clients;
        $contractables = $compagnie->contractables;
        $offres = $compagnie->offres;
        $contrats = $compagnie;

        return view('reservations.edit', compact('reservation', 'clients', 'contractables', 'offres', 'contrats', 'compagnie'));
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
        $reservation->update($this->prepareReservationData($request, $reservation));

        if ($request->paiement) {
            Paiement::create([
                'payable_id' => $reservation->id,
                'payable_type' => 'App\\Reservation',
                'montant' => $request->paiement,
            ]);
        }

        return redirect('/reservations');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->back();
    }

    protected function prepareReservationData(Request $request, ?Reservation $reservation = null): array
    {
        $compagnie = Auth::user()->compagnie;
        $contractableId = $request->input('contractable_id') ?? $request->input('contractable');
        $contractableId = $contractableId ?: $reservation?->contractable_id;

        $du = $request->filled('du') ? Carbon::parse($request->du) : null;
        $au = $request->filled('au') ? Carbon::parse($request->au) : null;

        $nombreJours = $this->calculateNombreJours($request, $du, $au, $reservation?->nombre_jours);

        $prixJournalier = $request->filled('prix_journalier') ? (float) $request->prix_journalier : null;
        $demiJournee = (float) ($request->input('demi_journee') ?? 0);
        $montantChauffeur = (float) ($request->input('montant_chauffeur') ?? 0);

        $total = null;
        if (!is_null($prixJournalier)) {
            $total = ($nombreJours ?: 0) * $prixJournalier + $demiJournee + $montantChauffeur;
        }

        $statut = $request->statut ?? ($reservation?->statut ?? Reservation::STATUS_EN_ATTENTE);

        $contractableType = $reservation?->contractable_type ?? ($compagnie->isHotel() ? 'App\\Chambre' : 'App\\Voiture');

        return [
            'contractable_id' => $contractableId,
            'contractable_type' => $contractableType,
            'client_id' => $request->client_id ?? $reservation?->client_id,
            'du' => $du,
            'au' => $au,
            'demi_journee' => $request->demi_journee,
            'montant_chauffeur' => $request->montant_chauffeur,
            'caution' => $request->caution,
            'note' => $request->note,
            'statut' => $statut,
            'nombre_jours' => $nombreJours,
            'total' => $total,
        ];
    }

    protected function calculateNombreJours(Request $request, ?Carbon $du, ?Carbon $au, ?int $fallback = null): ?int
    {
        if ($du && $au) {
            $days = $du->copy()->startOfDay()->diffInDays($au->copy()->startOfDay(), false);
            if ($days <= 0 && $au->greaterThan($du)) {
                $days = 1;
            }
            if ($days > 0) {
                return $days;
            }
        }

        if ($request->filled('nombre_jours')) {
            return max((int) $request->nombre_jours, 1);
        }

        return $fallback;
    }
}
