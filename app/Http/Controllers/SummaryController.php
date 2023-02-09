<?php

namespace App\Http\Controllers;

use App\Contrat;
use App\Jobs\CheckContractData;
use App\Jobs\SummarizeData;
use App\Models\Summary;
use App\Paiement;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function summarizeData(){
        SummarizeData::dispatch();
    }
}
