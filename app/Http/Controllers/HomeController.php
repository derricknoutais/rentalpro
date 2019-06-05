<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voiture;
use App\Contrat;

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
        $contrats_en_cours = Contrat::where('check_in', '>' ,now())->get()->sortBy('check_in');
        $contrats_en_retard = Contrat::where('check_in', '<', now())->whereNull('real_check_in')->get()->sortBy('check_in');

        return view('welcome', compact('voitures', 'contrats_en_cours', 'contrats_en_retard'));
    }
}
