<?php

namespace App\Http\Controllers;

use App\Contrat;
use App\Maintenance;
use App\Reservation;
use App\Voiture;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RapportAnalyticsController extends Controller
{
    public function index()
    {
        $presets = $this->availablePresets();
        $voitures = Voiture::select('id', 'immatriculation', 'marque', 'type')
            ->orderBy('immatriculation')
            ->get()
            ->map(function ($voiture) {
                return [
                    'id' => $voiture->id,
                    'label' => trim(implode(' ', array_filter([
                        $voiture->marque,
                        $voiture->type,
                        $voiture->immatriculation,
                    ]))),
                ];
            });

        return view('rapports.analytics', [
            'presets' => $presets,
            'defaultPreset' => 'this_month',
            'voitures' => $voitures,
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        [$start, $end, $label] = $this->resolveRange($request);
        $voitureId = $request->filled('voiture_id') ? (int) $request->get('voiture_id') : null;

        $rangeDays = $start->diffInDays($end) + 1;

        $contrats = Contrat::whereBetween('created_at', [$start, $end])
            ->when($voitureId, fn ($query) => $query->where('contractable_id', $voitureId))
            ->get();

        $reservationsQuery = Reservation::whereBetween('created_at', [$start, $end])
            ->when($voitureId, fn ($query) => $query->where('contractable_id', $voitureId));
        $reservations = $reservationsQuery->get();

        $totalSales = $contrats->sum(function (Contrat $contrat) {
            return $contrat->total();
        });

        $totalRentalDays = $contrats->sum('nombre_jours');
        $reservationCount = $reservations->count();
        $conversionCount = $reservations->where('statut', Reservation::STATUS_CONVERTIE)->count();

        $statusBreakdown = $reservations
            ->groupBy('statut')
            ->map(fn (Collection $items) => $items->count())
            ->all();

        $maintenanceCosts = Maintenance::whereBetween('created_at', [$start, $end])
            ->when($voitureId, fn ($query) => $query->where('voiture_id', $voitureId))
            ->get()
            ->sum(function (Maintenance $maintenance) {
                return ($maintenance->coût ?? 0) + ($maintenance->coût_pièces ?? 0);
            });

        $vehicleCount = Voiture::count();
        $daysCapacity = max($vehicleCount * $rangeDays, 1);
        $rentalRate = $daysCapacity > 0 ? round(($totalRentalDays / $daysCapacity) * 100, 2) : 0;

        $conversionRate = $reservationCount > 0 ? round(($conversionCount / $reservationCount) * 100, 2) : 0;
        $avgDailyRate = $totalRentalDays > 0 ? round($totalSales / $totalRentalDays, 2) : 0;
        $avgReservationLength = $contrats->count() > 0 ? round($totalRentalDays / $contrats->count(), 1) : 0;
        $roi = $maintenanceCosts > 0 ? round((($totalSales - $maintenanceCosts) / $maintenanceCosts) * 100, 2) : null;

        $timeline = Contrat::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_reservations'),
            DB::raw('SUM(nombre_jours) as total_days'),
            DB::raw('SUM(nombre_jours * prix_journalier + COALESCE(demi_journee,0) + COALESCE(montant_chauffeur,0)) as total_sales')
        )
            ->whereBetween('created_at', [$start, $end])
            ->when($voitureId, fn ($query) => $query->where('contractable_id', $voitureId))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return response()->json([
            'range' => [
                'label' => $label,
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
                'days' => $rangeDays,
            ],
            'metrics' => [
                'total_sales' => round($totalSales, 2),
                'reservations_count' => $reservationCount,
                'conversion_rate' => $conversionRate,
                'rental_rate' => $rentalRate,
                'average_daily_rate' => $avgDailyRate,
                'average_reservation_length' => $avgReservationLength,
            ],
            'revenue_vs_costs' => [
                'revenue' => round($totalSales, 2),
                'maintenance_costs' => round($maintenanceCosts, 2),
                'roi' => $roi,
            ],
            'status_breakdown' => $statusBreakdown,
            'timeline' => $timeline,
            'filters' => [
                'voiture_id' => $voitureId,
            ],
        ]);
    }

    protected function availablePresets(): array
    {
        return [
            'today' => 'Aujourd’hui',
            'this_week' => 'Cette semaine',
            'this_month' => 'Ce mois',
            'last_week' => 'La semaine dernière',
            'last_month' => 'Le mois dernier',
            'last_quarter' => 'Le trimestre dernier',
            'last_year' => 'L’année dernière',
            'past_1_year' => '12 derniers mois',
            'past_2_years' => '24 derniers mois',
            'past_3_years' => '36 derniers mois',
            'year_to_date' => 'Depuis le 1er janvier',
        ];
    }

    protected function resolveRange(Request $request): array
    {
        $preset = $request->get('preset');

        $startInput = $request->get('start_date');
        $endInput = $request->get('end_date');

        if ($startInput && $endInput) {
            $start = Carbon::parse($startInput)->startOfDay();
            $end = Carbon::parse($endInput)->endOfDay();

            return [$start, $end, $start->isoFormat('LL') . ' - ' . $end->isoFormat('LL')];
        }

        $now = Carbon::now();
        $start = $now->copy()->startOfMonth();
        $end = $now->copy()->endOfMonth();
        $label = $this->availablePresets()['this_month'];

        switch ($preset) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = $this->availablePresets()['today'];
                break;
            case 'this_week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                $label = $this->availablePresets()['this_week'];
                break;
            case 'last_week':
                $start = $now->copy()->subWeek()->startOfWeek();
                $end = $now->copy()->subWeek()->endOfWeek();
                $label = $this->availablePresets()['last_week'];
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $label = $this->availablePresets()['this_month'];
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                $label = $this->availablePresets()['last_month'];
                break;
            case 'last_quarter':
                $start = $now->copy()->subQuarter()->startOfQuarter();
                $end = $now->copy()->subQuarter()->endOfQuarter();
                $label = $this->availablePresets()['last_quarter'];
                break;
            case 'last_year':
                $start = $now->copy()->subYear()->startOfYear();
                $end = $now->copy()->subYear()->endOfYear();
                $label = $this->availablePresets()['last_year'];
                break;
            case 'past_1_year':
                $start = $now->copy()->subYear()->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = $this->availablePresets()['past_1_year'];
                break;
            case 'past_2_years':
                $start = $now->copy()->subYears(2)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = $this->availablePresets()['past_2_years'];
                break;
            case 'past_3_years':
                $start = $now->copy()->subYears(3)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = $this->availablePresets()['past_3_years'];
                break;
            case 'year_to_date':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfDay();
                $label = $this->availablePresets()['year_to_date'];
                break;
        }

        return [$start, $end, $label];
    }
}
