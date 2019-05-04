<?php

namespace App\Http\Controllers;

use App\Technicien;
use Illuminate\Http\Request;

class TechnicienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Technicien::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Technicien  $technicien
     * @return \Illuminate\Http\Response
     */
    public function show(Technicien $technicien)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Technicien  $technicien
     * @return \Illuminate\Http\Response
     */
    public function edit(Technicien $technicien)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Technicien  $technicien
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Technicien $technicien)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Technicien  $technicien
     * @return \Illuminate\Http\Response
     */
    public function destroy(Technicien $technicien)
    {
        //
    }
}
