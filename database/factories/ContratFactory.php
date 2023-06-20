<?php

namespace Database\Factories;

use App\Contrat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContratFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contrat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        $prix_journalier = $faker->randomElement($array = array(30000, 60000, 45000));
        return [

        'numéro' => strtoupper($faker->bothify('CL###/##/2019')),
        'compagnie_id' => 1,
        'au' => $au = $faker->dateTimeThisYear($max = 'now'),
        'du' => $du = $faker->dateTimeThisYear($max = 'now'),
        'nombre_jours' => Carbon::parse($au)->startOfDay()->diffInDays(Carbon::parse($du)->startOfDay()),
        'prix_journalier' => $prix_journalier,
        'caution' => 100000,
        'created_at' => now(),
        'cashier_facture_id' => rand(1,10)
        ];
    }
}
