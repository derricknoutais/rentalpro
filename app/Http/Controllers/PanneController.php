<?php

namespace App\Http\Controllers;

use App\Panne;
use App\Voiture;
use App\Contractable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request, Voiture $voiture)
    {
        for ($i = 0; $i < $request->nombrePannes; $i++) {
            $data[] = [
                'voiture_id' => $voiture->id,
                'compagnie_id' => Auth::user()->compagnie_id,
                'description' => $request['panne' . $i],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Panne::insert($data);

        return redirect()->back();
    }

    public function storeApi(Request $request)
    {
        $panne = Panne::create([
            'compagnie_id' => Auth::user()->compagnie_id,
            'contractable_id' => $request['contractable_id'],
            'description' => $request['description'],
        ]);
        return $panne;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Panne  $panne
     * @return \Illuminate\Http\Response
     */
    public function show(Panne $panne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Panne  $panne
     * @return \Illuminate\Http\Response
     */
    public function edit(Panne $panne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Panne  $panne
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Panne $panne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Panne  $panne
     * @return \Illuminate\Http\Response
     */
    public function destroy(Panne $panne)
    {
        $panne->delete();
        return response()->json([
            'message' => 'Panne deleted successfully',
        ]);
    }
}
