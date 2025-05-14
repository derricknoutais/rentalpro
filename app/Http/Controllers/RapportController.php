<?php

namespace App\Http\Controllers;

use App\Paiement;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Mail\RapportJournalierVente;
use Illuminate\Support\Facades\Mail;

class RapportController extends Controller
{
    public function paiementJournalier()
    {
        $paiements_airtelmoney = Paiement::where('created_at', '>', Carbon::today()->subDay()->startOfDay()->addHours(18))
            ->where('created_at', '<', Carbon::today()->setTime(18, 00, 00))
            ->where('type_paiement', 'Airtel Money')
            ->orderBy('type_paiement')
            ->get();
        $paiements_espece = Paiement::where('created_at', '>', Carbon::today()->subDay()->startOfDay()->addHours(18))
            ->where('created_at', '<', Carbon::today()->setTime(18, 00, 00))
            ->where('type_paiement', 'Espèce')
            ->orderBy('type_paiement')
            ->get();

        // Mail::to('derricknoutais@gmail.com')->send(new RapportJournalierVente());
        return view('rapports.paiement-journalier', compact('paiements_airtelmoney', 'paiements_espece'));
    }
    public function printPaiementJournalier($date)
    {
        // return Carbon::parse($date);
        $date = Carbon::parse($date);

        $paiements_airtelmoney = Paiement::where('created_at', '>', Carbon::parse($date)->subDay()->startOfDay()->addHours(18))
            ->where('created_at', '<', Carbon::parse($date)->addDay()->setTime(18, 00, 00))
            ->where('type_paiement', 'Airtel Money')
            ->orderBy('type_paiement')
            ->get();

        $paiements_espece = Paiement::where('created_at', '>', Carbon::parse($date)->subDay()->startOfDay()->addHours(18))
            ->where('created_at', '<', Carbon::parse($date)->addDay()->setTime(18, 00, 00))
            ->where('type_paiement', 'Espèce')
            ->orderBy('type_paiement')
            ->get();

        return view('print.paiement-journalier', compact('paiements_airtelmoney', 'paiements_espece', 'date'));
    }
}
