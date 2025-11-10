<?php

namespace App\Http\Controllers;

use App\Voiture;
use App\Contractable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContractableController extends Controller
{
    public function index()
    {
        $contractables = Auth::user()->compagnie->contractables;
        return view('contractables.index', compact('contractables'));
    }

    public function getApi()
    {
        $contractables = Voiture::where('compagnie_id', 1)->get();
        $contractables->loadMissing(['images']);
        $contractables->each(fn($contractable) => $this->appendPhotoUrls($contractable));

        return response()->json($contractables);
    }

    public function getFullApi()
    {
        $compagnie = Auth::user()->compagnie;

        $relations = ['pannes', 'documents', 'accessoires', 'contrats', 'contrats.client'];

        if ($compagnie->isVehicules()) {
            $relations[] = 'images';
        }

        $contractables = $compagnie->contractables()->with($relations)->get();
        $contractables->each(fn($contractable) => $this->appendPhotoUrls($contractable));

        return response()->json($contractables);
    }
    public function show($contractable_id)
    {
        $contractable = Auth::user()->compagnie->contractables->find($contractable_id);
        $relations = ['contrats', 'contrats.client', 'pannes', 'accessoires', 'documents'];

        if ($contractable && method_exists($contractable, 'images')) {
            $relations[] = 'images';
        }

        $contractable?->loadMissing($relations);
        $this->appendPhotoUrls($contractable);
        $contrats = $contractable->contrats->reverse()->take(3);
        $documents = Auth::user()->compagnie->documents;
        $accessoires = Auth::user()->compagnie->accessoires;

        return view('contractables.show', compact('contractable', 'contrats', 'documents', 'accessoires'));
    }
    public function create()
    {
        return view('contractables.create');
    }

    protected function appendPhotoUrls($contractable)
    {
        if (!$contractable) {
            return null;
        }

        if (!method_exists($contractable, 'images')) {
            $contractable->setAttribute('photo_urls', []);
            return $contractable;
        }

        $contractable->loadMissing(['images']);

        $photoUrls = $contractable->images
            ->map(function ($image) {
                $url = $image->url ?? $this->buildImageUrl($image->directory ?? null, $image->name ?? null);
                if (!$url) {
                    return null;
                }

                return [
                    'id' => $image->id ?? null,
                    'url' => $url,
                    'name' => $image->name ?? null,
                    'directory' => $image->directory ?? null,
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        $contractable->setAttribute('photo_urls', $photoUrls);

        return $contractable;
    }

    protected function buildImageUrl(?string $directory, ?string $filename): ?string
    {
        if (!$filename) {
            return null;
        }

        $path = trim($directory ? "{$directory}/{$filename}" : $filename, '/');

        try {
            return Storage::disk('do_spaces')->url($path);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
