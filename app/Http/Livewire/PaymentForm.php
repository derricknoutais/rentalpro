<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaymentForm extends Component
{
    public $contrat, $payer_avec_caution, $montant = 0;

    public function mount($contrat){
        $this->contrat = $contrat;
    }

    public function render()
    {
        return view('livewire.payment-form');
    }
}
