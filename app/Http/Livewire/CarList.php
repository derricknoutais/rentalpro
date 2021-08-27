<?php

namespace App\Http\Livewire;

use App\Voiture;
use Livewire\Component;

class CarList extends Component
{
    public $voitures;

    protected $rules = [
        'voitures' => 'required'
    ];

    public function mount(){
        $this->voitures = Voiture::all();
    }

    public function searchByState($state){
        if($state === 'all'){
            $this->voitures = Voiture::all();
            return 0;
        }
        $this->voitures = Voiture::where('etat', '=', $state)->get();
        // dd($this->voitures);
    }
    public function render()
    {
        return view('livewire.car-list');
    }
}
