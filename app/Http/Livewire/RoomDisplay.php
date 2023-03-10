<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RoomDisplay extends Component
{
    public $contractables;
    public $display;
    public function mount($contractables){
        return $this->contractables = $contractables;
        $this->display = $this->contractables[0];

    }

    public function afficheContractable($id){
        $this->display = $this->contractables->find($id);
    }

    public function render()
    {
        return view('livewire.room-display');
    }
}
