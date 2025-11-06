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

    public function getApi()
    {
        $contractables = Auth::user()->compagnie->contractables;
        return response()->json($contractables);
    }
    public function show($contractable_id)
    {
        $contractable = Auth::user()->compagnie->contractables->find($contractable_id);
        $contractable->loadMissing('contrats', 'contrats.client', 'pannes', 'accessoires', 'documents');
        $contrats = $contractable->contrats->reverse()->take(3);
        $documents = Auth::user()->compagnie->documents;
        $accessoires = Auth::user()->compagnie->accessoires;

        return view('contractables.show', compact('contractable', 'contrats', 'documents', 'accessoires'));
    }
    public function create()
    {
        return view('contractables.create');
    }
}
