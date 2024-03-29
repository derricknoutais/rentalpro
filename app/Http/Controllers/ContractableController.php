<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractableController extends Controller
{
    public function index()
    {
        $contractables = Auth::user()->compagnie->contractables;
        return view('contractables.index', compact('contractables'));
    }
    public function show($contractable_id)
    {
        $contractable = Auth::user()->compagnie->contractables->find($contractable_id);
        $contractable->loadMissing('contrats', 'contrats.client');
        $contrats = $contractable->contrats->reverse()->take(3);

        return view('contractables.show', compact('contractable', 'contrats'));
    }
    public function create()
    {
        return view('contractables.create');
    }
}
