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
        'etat' => 'Loué', //$faker->randomElement($array = array ('Disponible', 'Loué', 'Maintenance')),
        'prix' => $faker->randomElement($array = array (30000, 60000, 45000)),
        'douane' => $faker->randomElement($array = array(1000000, 500000, 200000, 800000)),
        'prix_achat' => $faker->randomElement($array = array(10000000, 5000000, 2000000, 8000000)),
        'transport' => $faker->randomElement($array = array(400000, 900000, 300000, 700000)),
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
    $prix_journalier = $faker->randomElement($array = array(30000, 60000, 45000));
    $nombre_jours = rand(1, 30);
     return [
        'numéro' => strtoupper($faker->bothify('CL###/##/2019')), 
        'check_out' => $faker->dateTimeInInterval($startDate = '-10 days', $interval = '+ 5 days', $timezone = null),
        'check_in' => $faker->dateTimeInInterval($startDate = '-10 days', $interval = '+ 5 days', $timezone = null),
        'prix_journalier' => $prix_journalier,
        'nombre_jours' => $nombre_jours,
        'caution' => 100000,
        'total' => $prix_journalier * $nombre_jours,
        'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = 'Africa/Libreville')
    ];
});

$factory->define(App\Maintenance::class, function (Faker $faker) {
    return [
        'voiture_id' => rand(1, 10),
        'technicien_id' => rand(1,3),
        'coût' => $faker->randomElement($array = array(50000, 75000, 90000))
    ];
});

$factory->define(App\Technicien::class, function (Faker $faker) {
    return [
        'nom' => $faker->firstName,
    ];
});