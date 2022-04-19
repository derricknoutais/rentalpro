<?php

namespace App\Http\Livewire;

use App\Voiture;
use Livewire\Component;

class CarDisplay extends Component
{
    public $cars;
    public $carToDisplay;
    public $filters = [
        'etat' => '*'
    ];

    public function mount(){
        $this->cars = Voiture::all();
    }
    public function addCarToDisplay(Voiture $car){
        $this->carToDisplay = $car;
    }
    public function updatedFiltersEtat($value){
        if($value === '*'){
            $this->cars = Voiture::all();
        } else {
            $this->cars = Voiture::where('etat', $value)->get();
        }

    }
    public function closeDisplay(){
        $this->carToDisplay = null;
    }

    public function render()
    {
        return view('livewire.car-display');
    }
}
