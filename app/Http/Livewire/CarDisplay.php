<?php

namespace App\Http\Livewire;

use App\Voiture;
use Livewire\Component;

class CarDisplay extends Component
{
    public $cars;
    public $carToDisplay;

    public function mount(){
        $this->cars = Voiture::all();
    }
    public function addCarToDisplay(Voiture $car){
        $this->carToDisplay = $car;
    }
    public function closeDisplay(){
        $this->carToDisplay = null;
    }

    public function render()
    {
        return view('livewire.car-display');
    }
}
