<?php

namespace App\Http\Controllers;

use App\Client;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationApiController extends Controller
{
    public function index(Request $request)
    {
        $compagnie = Auth::user()->compagnie;

        $query = Reservation::with(['contractable', 'client'])
            ->where('compagnie_id', $compagnie->id);

        if ($request->filled('start')) {
            $query->whereDate('au', '>=', Carbon::parse($request->start));
        }

        if ($request->filled('end')) {
            $query->whereDate('du', '<=', Carbon::parse($request->end));
        }

        if ($request->filled('contractable_id')) {
            $query->where('contractable_id', $request->contractable_id);
        }

        if ($request->filled('status')) {
            $statuses = Arr::wrap($request->status);
            $query->whereIn('statut', $statuses);
        }

        $reservations = $query->orderBy('du')->get();

        return $reservations->map(function (Reservation $reservation) {
            return $this->transformReservation($reservation);
        });
    }

    public function store(Request $request)
    {
        $data = $this->validatePayload($request);
        $reservation = $this->persist(new Reservation(), $data);

        return response()->json($this->transformReservation($reservation), 201);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorizeReservation($reservation);
        $data = $this->validatePayload($request, $reservation->id);

        $reservation = $this->persist($reservation, $data);

        return response()->json($this->transformReservation($reservation));
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);
        $reservation->delete();

        return response()->json(['message' => 'Réservation supprimée']);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $data = $request->validate([
            'statut' => 'required|in:' . implode(',', array_keys(Reservation::statusOptions())),
        ]);

        $reservation->update(['statut' => $data['statut']]);

        return response()->json($this->transformReservation($reservation->fresh(['contractable', 'client'])));
    }

    public function convert(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        if ($reservation->contrat_id) {
            return response()->json([
                'message' => 'Cette réservation est déjà liée à un contrat.',
            ], 422);
        }

        if ($reservation->statut === Reservation::STATUS_ANNULEE) {
            return response()->json([
                'message' => "Impossible de convertir une réservation annulée.",
            ], 422);
        }

        $reservation->update(['statut' => Reservation::STATUS_EN_COURS]);

        return response()->json([
            'message' => 'Redirection vers la création de contrat.',
            'redirect_url' => url('/contrats/create?reservation_id=' . $reservation->id),
        ]);
    }

    protected function validatePayload(Request $request, $reservationId = null): array
    {
        $compagnie = Auth::user()->compagnie;

        $rules = [
            'contractable_id' => 'required|integer',
            'client_id' => 'nullable|exists:clients,id',
            'du' => 'required|date',
            'au' => 'required|date|after:du',
            'demi_journee' => 'nullable|numeric',
            'montant_chauffeur' => 'nullable|numeric',
            'caution' => 'nullable|numeric',
            'note' => 'nullable|string',
            'statut' => 'nullable|in:' . implode(',', array_keys(Reservation::statusOptions())),
            'total' => 'nullable|numeric',
        ];

        $data = $request->validate($rules);

        $contractable = $compagnie->contractables()->findOrFail($data['contractable_id']);

        $data['contractable_id'] = $contractable->id;
        $data['contractable_type'] = get_class($contractable);
        $data['statut'] = $data['statut'] ?? Reservation::STATUS_EN_ATTENTE;
        $data['nombre_jours'] = Carbon::parse($data['du'])->diffInDays(Carbon::parse($data['au'])) ?: 1;

        return $data;
    }

    protected function persist(Reservation $reservation, array $data): Reservation
    {
        $reservation->fill($data);
        $reservation->save();

        return $reservation->fresh(['contractable', 'client']);
    }

    protected function transformReservation(Reservation $reservation): array
    {
        return [
            'id' => $reservation->id,
            'title' => $this->buildTitle($reservation),
            'start' => optional($reservation->du)->toIso8601String(),
            'end' => optional($reservation->au)->toIso8601String(),
            'statut' => $reservation->statut,
            'status_label' => $reservation->status_label,
            'status_color' => $reservation->status_color,
            'cssClass' => 'status-' . $reservation->statut,
            'note' => $reservation->note,
            'nombre_jours' => $reservation->nombre_jours,
            'demi_journee' => $reservation->demi_journee,
            'montant_chauffeur' => $reservation->montant_chauffeur,
            'caution' => $reservation->caution,
            'client' => $reservation->client,
            'contractable' => $reservation->contractable,
            'link' => url('/reservations/' . $reservation->id . '/edit'),
        ];
    }

    protected function buildTitle(Reservation $reservation): string
    {
        $parts = [];
        if ($reservation->contractable) {
            $parts[] = $reservation->contractable->immatriculation
                ?? $reservation->contractable->nom
                ?? 'Contractable';
        }
        if ($reservation->client) {
            $parts[] = trim(($reservation->client->nom ?? '') . ' ' . ($reservation->client->prenom ?? ''));
        }

        return implode(' · ', array_filter($parts)) ?: 'Réservation #' . $reservation->id;
    }

    protected function authorizeReservation(Reservation $reservation): void
    {
        abort_unless($reservation->compagnie_id === Auth::user()->compagnie_id, 403);
    }
}
