<?php

namespace App\Jobs;

use App\Contrat;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckContractData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contrats = Contrat::all();
        foreach($contrats as $contrat){
            $n_day = $contrat->du->diffInDays($contrat->au);
            $total = $n_day * $contrat->prix_journalier;
            if($total !== $contrat->total){
                Log::info('Contrat N ' . $contrat->numero . ' Corrompus. Total Inscrit : ' . $contrat->total . '. Total Calcule : ' . $total);
                $contrat->update([
                    'total' => $total
                ]);
            }
        }
        return 1;
    }
}
