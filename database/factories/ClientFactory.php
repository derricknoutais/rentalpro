<?php

namespace Database\Factories;

use App\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'nom' => $faker->lastName,
            'prenom' => $faker->firstName,
            'compagnie_id' => 1,
            'adresse' => $faker->streetAddress,
            'numero_permis' => strtoupper($faker->bothify('B0##?#??###?#')),
            'phone1' => $faker->phoneNumber,
            'mail' => $faker->email,
            'ville' => $faker->city
        ];
    }
}
