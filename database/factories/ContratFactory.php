<?php

namespace Database\Factories;

use App\Contrat;
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
        'au' => $faker->dateTimeInInterval($startDate = '-365 days', $interval = '+ 800 days', $timezone = null),
        'du' => $faker->dateTimeInInterval($startDate = '-365 days', $interval = '+ 800 days', $timezone = null),
        'prix_journalier' => $prix_journalier,
        'caution' => 100000,
        'created_at' => $faker->dateTimeInInterval($startDate = '-365 days', $interval = '+ 800 days', $timezone = null),
        'cashier_facture_id' => rand(1,10)
        ];
    }
}
