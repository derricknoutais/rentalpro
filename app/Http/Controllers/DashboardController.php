<?php

namespace App\Http\Controllers;

use App\Client;
use App\Metric;
use App\Contrat;
use App\Paiement;
use App\Reservation;
use App\Contractable;
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
        $contrats = null;
        $offres = null;
        $contractables = null;


        if (isset($compagnie)) {
            $contrats = $compagnie->contrats;
            $offres = $compagnie->offres;
            $contractables = $compagnie->contractables;
        } else {
            return "Veuillez contacter le +24174229633 pour ouvir une compte Compagnie";
        }



        // $contractables = Contractable::query()->get();

        $reservations = Reservation::all();

        return view('dashboard.index', compact('dashboard', 'columnChartModel', 'contractables', 'compagnie', 'reservations', 'offres', 'contrats', 'reservations', 'clients'));
    }
}
