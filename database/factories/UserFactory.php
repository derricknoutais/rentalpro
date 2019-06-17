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
$factory->define(App\Compagnie::class, function(Faker $faker){
    return [
        'numero_contrat' => 0,
        'nom' => $faker->company 
    ];
});

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'compagnie_id' => 1,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(App\Voiture::class, function(Faker $faker){
    return [
        'compagnie_id' => 1,
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
        'compagnie_id' => 1,
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
        'compagnie_id' => 1, 
        'check_out' => $faker->dateTimeInInterval($startDate = '-365 days', $interval = '+ 800 days', $timezone = null),
        'check_in' => $faker->dateTimeInInterval($startDate = '-365 days', $interval = '+ 800 days', $timezone = null),
        'prix_journalier' => $prix_journalier,
        'nombre_jours' => $nombre_jours,
        'caution' => 100000,
        'total' => $prix_journalier * $nombre_jours,
        'created_at' => $faker->dateTimeInInterval($startDate = '-365 days', $interval = '+ 800 days', $timezone = null),
    ];
});

$factory->define(App\Maintenance::class, function (Faker $faker) {
    return [
        'titre' => $faker->text,
        'compagnie_id' => 1,
        'voiture_id' => rand(1, 10),
        'technicien_id' => rand(1,3),
        'coût' => $faker->randomElement($array = array(50000, 75000, 90000))
    ];
});

$factory->define(App\Technicien::class, function (Faker $faker) {
    return [
        'compagnie_id' => 1,
        'nom' => $faker->firstName,
    ];
});

$factory->define(App\Panne::class, function (Faker $faker){
    return [
        'compagnie_id' => 1,
        'voiture_id' => rand(1,10),
        'description' => $faker->sentence,
        'etat' => $faker->randomElement($array = array( 'non-résolue', 'résolue', 'en-maintenance'))
    ];
});