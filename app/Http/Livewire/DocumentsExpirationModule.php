<?php

namespace App\Http\Livewire;

use App\Document;
use App\Voiture;
use Livewire\Component;

class DocumentsExpirationModule extends Component
{
    public $voitures;

    public function mount(){
        $this->voitures = Voiture::with('documents')->get();
    }

    public function render()
    {
        return view('livewire.documents-expiration-module');
    }
}
