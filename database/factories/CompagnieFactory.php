<?php

namespace Database\Factories;

use App\Compagnie;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompagnieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Compagnie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->company,
            'type' => 'véhicules'
        ];
    }
}
