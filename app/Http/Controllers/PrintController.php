<?php

namespace App\Http\Controllers;

use App\Contrat;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function print(Contrat $contrat, $type_compagnie, $size){
        
        $contrat->loadMissing('client', 'contractable');

        return view('print.' . $type_compagnie . '-' . $size, compact('contrat'));
    }
}
