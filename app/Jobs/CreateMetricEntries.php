<?php

namespace App\Jobs;

use App\Metric;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CreateMetricEntries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        //
        $columns = ['annee', 'trimestre', 'mois', 'semaine', 'jour'];

        // Cree l'annee en cours
        $this->createMetric([
            'annee' => now()->year,
            'type' => 'annuel'
        ]);
        // Cree le trimestre en cours
        $this->createMetric(
            [
                'annee' => now()->year,
                'trimestre' => now()->quarter,

                'type' => 'trimestriel',
            ]
        );
        // Cree le mois en cours
        $this->createMetric([
            'annee' => now()->year,
            'trimestre' => now()->quarter,
            'mois' => now()->month,
            'mois_label' => now()->locale('fr')->monthName,
            'type' => 'mensuel'
        ]);
        // Cree la semaine en cours
        $this->createMetric([
            'annee' => now()->year,
            'trimestre' => now()->quarter,
            'mois' => now()->month,
            'mois_label' => now()->locale('fr')->monthName,
            'semaine' => now()->week,
            'type' => 'hebdomadaire'
        ]);
        // Cree le jour en cours
        $this->createMetric([
            'annee' => now()->year,
            'trimestre' => now()->quarter,
            'mois' => now()->month,
            'mois_label' => now()->locale('fr')->monthName,
            'semaine' => now()->week,
            'jour' => now()->day,
            'jour_semaine_code' => now()->dayOfWeek,
            'jour_semaine_long' =>now()->dayName,
            'type' => 'journalier'
        ]);

    }

    protected function createMetric( $dateTime ){

        if(
            ! Metric::where('checksum', $check = Metric::generateCheckSum($dateTime))->count()
            ){
            return Metric::create(
                array_merge(
                    ['checksum' => $check],
                    $dateTime
                )

            );
        }
    }

}
