<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voiture;
use App\Contrat;
use App\Chambre;
use App\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContratCréé;
use Nexmo\Laravel\Facade\Nexmo;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('/dashboard');
    }
    public function welcome(){
        $compacted = null;
        $clients = Client::all();
        if(Auth::user()->compagnie->isVehicules()){
            $voitures = Voiture::with('contrats')->get();
            $contrats = Contrat::with('contractable', 'client')->get();
            $contrats_en_cours = Contrat::where('du', '>' ,now())->get()->sortBy('du');
            $contrats_en_retard = Contrat::where('du', '<', now())->whereNull('real_check_out')->get()->sortBy('du');
            $compacted = compact('voitures', 'contrats_en_cours', 'contrats_en_retard', 'contrats', 'clients');
            if(sizeof($contrats) === 0){
                return view('welcome_no_contrats');
            }
        }
        else if(Auth::user()->compagnie->isHotel())
        {
            $chambres = Chambre::with('contrats')->get();
            foreach ($chambres as $chambre ) {
                $chambre->contrat_en_cours = $chambre->contratEnCours();
            }

            $contrats = Contrat::where('compagnie_id',Auth::user()->compagnie_id)->get();
            $compacted = compact('chambres', 'contrats', 'clients');
        }

        return view('welcome', $compacted);
        // Nexmo::message()->send([
        //     'to'   => '24107158215',
        //     'from' => 'STA',
        //     'text' => 'Bienvenue sur Rental Pro ' . Auth::user()->name
        // ]);
    }
}
