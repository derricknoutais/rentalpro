<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voiture;
use App\Contrat;
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
        return view('home');
    }
    public function welcome(){
        
        $voitures = Voiture::with('contrats')->get();
        $contrats = Contrat::with('voiture', 'client')->get();
        $contrats_en_cours = Contrat::where('check_in', '>' ,now())->get()->sortBy('check_in');
        $contrats_en_retard = Contrat::where('check_in', '<', now())->whereNull('real_check_in')->get()->sortBy('check_in');
        // Nexmo::message()->send([
        //     'to'   => '24107158215',
        //     'from' => 'STA',
        //     'text' => 'Bienvenue sur Rental Pro ' . Auth::user()->name 
        // ]);
        return view('welcome', compact('voitures', 'contrats_en_cours', 'contrats_en_retard', 'contrats'));
        
    }
}
