<?php

namespace Database\Factories;

use App\Voiture;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoitureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voiture::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'compagnie_id' => 1,
            'immatriculation' => strtoupper($faker->bothify('??-###-??')),
            'chassis' => strtoupper($faker->bothify('##??##??###??###?')),
            'annee' => $faker->numberBetween($min = 2010, $max = 2018),
            'marque' => 'Toyota',
            'type' => $faker->randomElement($array = array ('Corolla ZZE120', 'Corolla ZZE140', 'Rav4 IV', 'Rav4 III')),
            'etat' =>  $faker->randomElement($array = array ('Disponible', 'Loué', 'Maintenance')), //'Disponible',
            'prix' => $faker->randomElement($array = array (30000, 60000, 45000)),
            'douane' => $faker->randomElement($array = array(1000000, 500000, 200000, 800000)),
            'prix_achat' => $faker->randomElement($array = array(10000000, 5000000, 2000000, 8000000)),
            'transport' => $faker->randomElement($array = array(400000, 900000, 300000, 700000)),
        ];
    }
}
