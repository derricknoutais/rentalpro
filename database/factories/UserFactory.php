<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Voiture::class, function(Faker $faker){
    return [
        'immatriculation' => strtoupper($faker->bothify('??-###-??')),
        'chassis' => strtoupper($faker->bothify('##??##??###??###?')),
        'annee' => $faker->numberBetween($min = 2010, $max = 2018),
        'marque' => 'Toyota',
        'type' => $faker->randomElement($array = array ('Corolla ZZE120', 'Corolla ZZE140', 'Rav4 IV', 'Rav4 III')),
        'etat' => $faker->randomElement($array = array ('Disponible', 'Loué', 'Maintenance')),
        'prix' => $faker->randomElement($array = array (30000, 60000, 45000))
    ];
});

$factory->define(App\Client::class, function(Faker $faker){
    return [
        'nom' => $faker->lastName,
        'prenom' => $faker->firstName,
        'adresse' => $faker->streetAddress,
        'numero_permis' => strtoupper($faker->bothify('B0##?#??###?#')),
        'phone1' => $faker->phoneNumber,
        'mail' => $faker->email,
        'ville' => $faker->city
    ];
});

$factory->define(App\Contrat::class, function(Faker $faker){
     return [
        'numéro' => strtoupper($faker->bothify('CL###/##/2019')), 
        'check_out' => $faker->dateTimeInInterval($startDate = '-10 days', $interval = '+ 5 days', $timezone = null),
        'check_in' => $faker->dateTimeInInterval($startDate = '-10 days', $interval = '+ 5 days', $timezone = null),
        'prix_journalier' => $faker->randomElement($array = array(30000, 60000, 45000)),
        'nombre_jours' => 5,
        'caution' => 100000,
        'total' => 150000
    ];
});