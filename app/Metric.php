<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Metric extends Model
{
    protected $guarded = [];
    public $columns = ['annee', 'trimestre', 'mois', 'semaine', 'jour'];
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        // static::creating( function(Metric $reporting){
        //     $reporting->checksum = $this->generateCheckSum($reporting);
        // });
    }

    protected static function insere($model)
    {
        $columnsToUpdate = Metric::columnsToUpdate($model);
        $array_of_checksums = Metric::generateChecksumsFrom($model->created_at);

        foreach ($array_of_checksums as $type => $checksum) {
            $metric = Metric::where('checksum', $checksum)->first() ? ($metric = Metric::where('checksum', $checksum)->first()) : ($metric = Metric::createMetric($model->created_at, $checksum));

            foreach ($columnsToUpdate as $key => $value) {
                $metric->increment($key, $value);
            }
        }
    }
    protected static function maj($model, $oldAttributes)
    {
        $array_of_checksums = Metric::generateChecksumsFrom($model->created_at);
        foreach ($array_of_checksums as $checksum) {
            $metric = Metric::where('checksum', $checksum)->first();
            if ($metric) {
                $columnsToUpdate = Metric::columnsToUpdate($model, $oldAttributes);
                foreach ($columnsToUpdate as $column => $value) {
                    $metric->decrement($column, $value);
                }
                $columnsToUpdate = Metric::columnsToUpdate($model);
                foreach ($columnsToUpdate as $column => $value) {
                    $metric->increment($column, $value);
                }
            }
        }
    }
    protected static function supprime(Contrat $contrat)
    {
        $array_of_checksums = Metric::generateChecksumsFrom($contrat->created_at);
        // $check = [];
        foreach ($array_of_checksums as $checksum) {
            $reporting = Metric::where('checksum', $checksum)->first();
            $reporting->decrementFrom($contrat);
            // $check[] = $reporting;
        }
    }

    public static function renderColumns($date)
    {
        if ($date) {
            return [
                'annee' => $date->year,
                'trimestre' => $date->quarter,
                'mois' => $date->month,
                'semaine' => $date->week,
                'jour' => $date->day,
            ];
        }
    }
    protected static function generateCheckSum($dateTime)
    {
        $checkSum = '';
        $columns = ['annee', 'trimestre', 'mois', 'semaine', 'jour'];
        if (!is_array($dateTime)) {
            $dateTime = Metric::renderColumns($dateTime);
        }
        if ($dateTime) {
            foreach ($dateTime as $key => $val) {
                if (in_array($key, $columns)) {
                    if ($val === null) {
                        $checkSum .= '-' . 0;
                    } else {
                        $checkSum .= '-' . $val;
                    }
                }
            }
        }
        return substr($checkSum, 1);
    }
    protected static function generateChecksumsFrom($date)
    {
        // Render Full Array of Checksums Values [ 'annee' => '2023' , ... , 'jour' => '31'  ]
        $array_columns = Metric::renderColumns($date);

        // Generate the longest checksum
        $checkSum = Metric::generateCheckSum($array_columns);

        // Get All Checksums based on the longest chacksum
        // End Purpose is to report on : Anual/Quarter/Month/Week/Daily Metric
        // ['annee' => '2023', 'trimestre' => '2023-3', 'mois' => '2023-3-8'7]
        return Metric::generateAllCheckSumsToReport($checkSum);
    }
    protected static function generateAllCheckSumsToReport($checkSum)
    {
        // 2023-1-11
        $checks = [];
        $columns = ['annee', 'trimestre', 'mois', 'semaine', 'jour'];
        $checks = explode('-', $checkSum); // 2023 - 1 - 11
        $explodedCheckSum = array_combine(array_splice($columns, 0, sizeof($checks)), $checks);
        $temp = '';
        $finalChecks = [];

        foreach ($columns as $col) {
            if (isset($finalChecks[$col])) {
                $finalChecks[$col] = $col === 'annee' ? $explodedCheckSum[$col] : $temp . '-' . $explodedCheckSum[$col];
                $temp = $finalChecks[$col];
            }
        }
        if (isset($finalChecks['semaine'])) {
            $weeklyCheck = explode('-', $finalChecks['semaine']);
            $weeklyCheck[1] = $weeklyCheck[2] = '0';
            $finalWeeklyCheck = '';
            foreach ($weeklyCheck as $period) {
                $finalWeeklyCheck .= '-' . $period;
            }
            $finalChecks['semaine'] = substr($finalWeeklyCheck, 1);
        }
        return $finalChecks;
    }

    protected static function createMetric($date, $checksum)
    {
        $date_parsed = Carbon::parse($date);
        $count = substr_count($checksum, '-');
        $ex = explode('-', $checksum);
        $type = '';
        $trimestre_label = null;
        $semaine_label = null;
        $mois_label = null;
        $jour_semaine_court = null;
        $jour_semaine_long = null;
        switch ($count) {
            case 0:
                $type = 'annee';
                break;
            case 1:
                $type = 'trimestre';
                $trimestre_label =
                    Carbon::parse($date)
                        ->startOfQuarter()
                        ->format('d M') .
                    ' - ' .
                    Carbon::parse($date)
                        ->endOfQuarter()
                        ->format('d M');
                break;
            case 2:
                $type = 'mois';
                $mois_label = Carbon::parse($date)->format('M');
                $trimestre_label =
                    Carbon::parse($date)
                        ->startOfQuarter()
                        ->format('d M') .
                    ' - ' .
                    Carbon::parse($date)
                        ->endOfQuarter()
                        ->format('d M');
                break;
            case 3:
                $type = 'semaine';
                $mois_label = Carbon::parse($date)->format('M');
                $semaine_label =
                    Carbon::parse($date)
                        ->startOfWeek()
                        ->format('d M') .
                    ' - ' .
                    Carbon::parse($date)
                        ->endOfWeek()
                        ->format('d M');
                $trimestre_label =
                    Carbon::parse($date)
                        ->startOfQuarter()
                        ->format('d M') .
                    ' - ' .
                    Carbon::parse($date)
                        ->endOfQuarter()
                        ->format('d M');
                break;
            case 4:
                $type = 'jour';
                $trimestre_label =
                    Carbon::parse($date)
                        ->startOfQuarter()
                        ->format('d M') .
                    ' - ' .
                    Carbon::parse($date)
                        ->endOfQuarter()
                        ->format('d M');
                $mois_label = Carbon::parse($date)->format('M');
                $semaine_label =
                    Carbon::parse($date)
                        ->startOfWeek()
                        ->format('d M') .
                    ' - ' .
                    Carbon::parse($date)
                        ->endOfWeek()
                        ->format('d M');
                $jour_semaine_court = Carbon::parse($date)->isoFormat('ddd');
                $jour_semaine_long = Carbon::parse($date)->isoFormat('dddd');
                break;
            default:
                break;
        }

        return Metric::create(array_merge(Metric::renderColumns($date), compact('checksum', 'type', 'trimestre_label', 'mois_label', 'semaine_label', 'jour_semaine_court', 'jour_semaine_long')));
    }

    protected static function columnsToUpdate($model, $data = null)
    {
        $class = get_class($model);

        switch ($class) {
            case 'App\\Contrat':
                if ($data) {
                    $model = new Contrat($data);
                }
                return [
                    'chiffre_affaires' => $model->total(),
                    'nombre_jours' => $model->nombre_jours,
                    'nombre_locations' => 1,
                ];
                break;
            case 'App\\Paiement':
                return [
                    'paiements_percus' => $model->montant,
                ];
                break;
            case 'App\\Maintenance':
                return [
                    'cout_main_oeuvre' => $model->coût,
                    'cout_pieces' => $model->coût_pièces,
                    'nombre_maintenances' => 1,
                    'nombre_pannes' => $model->pannes->count(),
                ];
                break;
            default:
                break;
        }
    }
}
