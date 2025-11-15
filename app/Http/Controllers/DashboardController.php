<?php

namespace App\Http\Controllers;

use App\Client;
use App\Metric;
use App\Paiement;
use App\Contrat;
use App\Voiture;
use App\Reservation;
use App\Contractable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Asantibanez\LivewireCharts\Models\LineChartModel;

class DashboardController extends Controller
{
    public function index()
    {
        $checksums = collect(Metric::generateChecksumsFrom(now()))->only(['mois', 'semaine', 'jour']);
        // $reporting = Metric::whereIn('checksum', $checksums->values())->get();

        // $reporting = array_combine(['mois', 'semaine', 'jour' ], $reporting->toArray());

        $paiements_by_months = Paiement::whereYear('created_at', '2021')->select(
            DB::raw('sum(montant) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%m/%Y') as months"),
        )->orderBy('months')->groupBy('months')->get();
        // Ids des Contrats de l'Annee
        $contrats_in_year_ids = Contrat::whereYear('du', now()->format('Y'))->pluck('id');
        // Ids des Contrats de l'Annee Derniere
        $last_year_contrats_ids = Contrat::whereYear('du', now()->format('Y') - 1)->pluck('id');

        // 1st Card ()
        $dashboard['paiements_annuels'] =
            Paiement::where('payable_type', 'App\\Contrat')
            ->whereIn('payable_id', $contrats_in_year_ids)
            ->sum('montant');

        $dashboard['last_year_payments'] =
            Paiement::where('payable_type', 'App\\Contrat')
            ->whereIn('payable_id', $last_year_contrats_ids)
            ->sum('montant');

        // 2nd Card ()
        $dashboard['nb_locations'] = Contrat::whereYear('du', now()->format('Y'))->sum('nombre_jours');
        $dashboard['last_year_nb_locations'] = Contrat::whereYear('du', now()->format('Y') - 1)->sum('nombre_jours');

        // 3rd Card ()
        if (Contrat::whereYear('du', now()->format('Y'))->sum('total')) {
            $dashboard['payment_rate'] = ($dashboard['paiements_annuels'] / Contrat::whereYear('du', now()->format('Y'))->sum('total')) * 100;
        }
        $dashboard['last_year_payment_rate'] = null;
        if (Contrat::whereYear('du', now()->format('Y') - 1)->sum('total')) {
            $dashboard['last_year_payment_rate'] = $dashboard['last_year_payments'] / Contrat::whereYear('du', now()->format('Y') - 1)->sum('total') * 100;
        }
        // return Paiement::all()->sum('montant');
        $columnChartModel = (new LineChartModel())->setTitle('Paiements');

        foreach ($paiements_by_months as $pay) {
            $columnChartModel->addPoint($pay->months, $pay->sums, '#f6ad55');
        }
        $clients = Client::all();
        $clients->toArray();
        $compagnie = Auth::user()->compagnie;
        $contrats = collect();
        $offres = collect();
        $contractables = collect();


        if (isset($compagnie)) {
            $contrats = $compagnie->contrats;
            $offres = $compagnie->offres;
            $contractables = $compagnie->contractables;
        } else {
            return "Veuillez contacter le +24174229633 pour ouvir une compte Compagnie";
        }



        // $contractables = Contractable::query()->get();

        $reservations = Reservation::all();

        $today = now();
        $activeContracts = $contrats->filter(function (Contrat $contrat) use ($today) {
            if (is_null($contrat->du) || is_null($contrat->au)) {
                return false;
            }
            return $contrat->du->lte($today) && $contrat->au->gte($today);
        });

        $upcomingReservations = $reservations
            ->filter(fn ($reservation) => in_array($reservation->statut, [
                Reservation::STATUS_EN_ATTENTE,
                Reservation::STATUS_CONFIRME,
                Reservation::STATUS_EN_COURS,
            ]))
            ->sortBy('du')
            ->take(5);

        $recentPayments = Paiement::latest()->take(5)->get();

        $latestContracts = $contrats->sortByDesc('created_at')->take(5);

        $fleetSize = $contractables->count();
        $fleetInUse = $activeContracts->pluck('contractable_id')->unique()->count();
        $fleetUtilization = $fleetSize > 0 ? round(($fleetInUse / $fleetSize) * 100, 1) : null;

        $dashboardSummary = [
            'revenue' => $dashboard['paiements_annuels'],
            'payment_rate' => $dashboard['payment_rate'] ?? null,
            'days_rented' => $dashboard['nb_locations'],
            'pending_reservations' => $reservations->where('statut', Reservation::STATUS_EN_ATTENTE)->count(),
            'active_contracts' => $activeContracts->count(),
            'fleet_utilization' => $fleetUtilization,
        ];

        $todayStart = $today->copy()->startOfDay();
        $todayDateString = $todayStart->toDateString();
        $upcomingDocumentExpirations = collect();

        if ($compagnie->isVehicules()) {
            $upcomingDocumentExpirations = DB::table('voiture_documents as vd')
                ->join('voitures as v', 'v.id', '=', 'vd.voiture_id')
                ->select(
                    'v.id as voiture_id',
                    'v.immatriculation',
                    DB::raw('MIN(vd.date_expiration) as next_expiration')
                )
                ->where('v.compagnie_id', $compagnie->id)
                ->whereNotNull('vd.date_expiration')
                ->whereDate('vd.date_expiration', '>=', $todayDateString)
                ->groupBy('v.id', 'v.immatriculation')
                ->orderBy('next_expiration')
                ->get();

            if ($upcomingDocumentExpirations->isNotEmpty()) {
                $activeContractsByVoiture = Contrat::query()
                    ->where('contractable_type', Voiture::class)
                    ->whereIn('contractable_id', $upcomingDocumentExpirations->pluck('voiture_id'))
                    ->whereNotNull('du')
                    ->whereDate('du', '<=', $todayDateString)
                    ->where(function ($query) use ($todayDateString) {
                        $query->whereNull('au')
                            ->orWhereDate('au', '>=', $todayDateString);
                    })
                    ->with('client:id,nom,prenom,telephone')
                    ->get()
                    ->keyBy('contractable_id');

                $upcomingDocumentExpirations = $upcomingDocumentExpirations->map(function ($expiration) use ($activeContractsByVoiture, $todayStart) {
                    $expirationDate = Carbon::parse($expiration->next_expiration)->startOfDay();
                    $expiration->next_expiration = $expirationDate;
                    $expiration->days_remaining = $todayStart->diffInDays($expirationDate);
                    $activeContract = $activeContractsByVoiture->get($expiration->voiture_id);
                    $expiration->client = $activeContract ? $activeContract->client : null;

                    return $expiration;
                });
            }
        }

        return view('dashboard.index', compact(
            'dashboard',
            'dashboardSummary',
            'columnChartModel',
            'contractables',
            'compagnie',
            'reservations',
            'offres',
            'contrats',
            'clients',
            'activeContracts',
            'upcomingReservations',
            'recentPayments',
            'latestContracts',
            'upcomingDocumentExpirations'
        ));
    }
}
