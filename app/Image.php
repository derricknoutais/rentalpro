<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function voitures()
    {
        return $this->belongsToMany(Voiture::class, 'voiture_images');
    }

    public function getUrlAttribute()
    {
        return Storage::disk('do_spaces')->url($this->directory . '/' . $this->name);
    }
}
