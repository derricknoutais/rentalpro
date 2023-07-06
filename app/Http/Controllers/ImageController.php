<?php

namespace App\Http\Controllers;

use App\Client;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uploadedFile = $request->file('clientId');
        // Validate
        if (!$request->hasFile('clientId')) {
            return response()->json([
                'error' => 'There is no image present'
            ], 400);
        }
        $request->validate([
            'clientId' => 'required|file|image'
        ]);
        // Save
        // $path = $request->file('clientId')->store('public/images');

        $path = Storage::disk('do_spaces')->putFile('permis', $uploadedFile, 'public');

        if (!$path) {
            return response()->json([
                'error' => 'The file could not be saved'
            ], 400);
        }

        $image = Image::create([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize(),
        ]);
        return $image->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image, Client $client)
    {
        Storage::disk('do_spaces')->delete('permis/' . $image->name);
        $image->delete();
        return $client->update(['image_id' => null]);
    }
}
