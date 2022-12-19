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
        'au' => $faker->dateTimeThisYear($max = 'now'),
        'du' => $faker->dateTimeThisYear($max = 'now'),
        'prix_journalier' => $prix_journalier,
        'caution' => 100000,
        'created_at' => $faker->dateTimeThisYear($max = 'now'),
        'cashier_facture_id' => rand(1,10)
        ];
    }
}
