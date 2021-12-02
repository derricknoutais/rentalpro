<?php

namespace App\Http\Livewire;

use App\User;
use App\ApiSetting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ApiSettingComponent extends Component
{
    public $tenant_id, $book_id, $exercise_id, $debit_account_id, $credit_account_id, $debit_label, $credit_label;

    public function saveApiSettings(){
        ApiSetting::updateOrCreate(
            ['compagnie_id' => Auth::user()->compagnie_id],
            [
                'gescash_tenant_id' => $this->tenant_id,
                'gescash_book_id' => $this->book_id ,
                'gescash_exercise_id' => $this->exercise_id ,
                'gescash_debit_account_id' => $this->debit_account_id ,
                'gescash_debit_label' => $this->debit_label ,
                'gescash_credit_account_id' => $this->credit_account_id ,
                'gescash_credit_label' => $this->credit_label
            ]
        );
    }

    public function render()
    {
        return view('livewire.api-setting-component');
    }
}
