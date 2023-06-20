<?php

use Illuminate\Database\Seeder;
use App\Jobs\CreateMetricEntries;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        CreateMetricEntries::dispatch();
        $documents = ['Carte Grise', 'Visite Technique', 'Assurance', 'Carte Extincteur'];
        $accessoires = ['Crick', 'Triangle', 'Manivelle', 'Calle Métallique', 'Pneu Secours', 'Extincteur', 'Gilet', 'Trousse Secours'];

        $sta = App\Compagnie::factory()->create([
            'nom' => 'STA',
            'type' => 'véhicules'
        ]);

        $orisha = App\Compagnie::factory()->create([
            'nom' => 'Orisha Inn',
            'type' => 'hôtel'
        ]);


        App\User::factory()->create([
            'name' => 'Kevin',
            'email' => 'kevin@gmail.com',
            'compagnie_id' => 1,
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret

        ]);
        App\User::factory()->create([
            'name' => 'Amoure',
            'email' => 'amoure@gmail.com',
            'compagnie_id' => 2,
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret

        ]);

        foreach ($documents as $document) {
            App\Document::create([
                'type' => $document,
                'compagnie_id' => 1
            ]);
        }
        foreach ($accessoires as $accessoire) {
            App\Accessoire::create([
                'type' => $accessoire,
                'compagnie_id' => 1
            ]);
        }
        App\Client::factory()->count(10)->create();

        $chambre_type = ['Budget', 'VIP' ];
        $prix = [40000, 60000 ];

        for ($i=0; $i < 9; $i++) {
            $chambre = App\Chambre::create([
                'nom' => '10' . $i,
                'compagnie_id' => 2,
                'type' => $chambre_type[$rand = rand(0, 1)],
                'prix_journalier' => $prix[$rand]
            ]);
            // App\Contrat::factory()->count(1)->create([
            //     'compagnie_id' => 1,
            //     'contractable_id' => $chambre->id,
            //     'contractable_type' => 'App\\Chambre',
            //     'client_id' => rand(0,9),
            //     'prix_journalier' => $chambre->prix_journalier,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);
        }


        App\Voiture::factory()->count(10)->create()->each(function($voiture) use ($accessoires, $documents){
            if($voiture->etat == 'Loué'){
                App\Contrat::factory()->count(5)->create([
                    'contractable_id' => $voiture->id,
                    'contractable_type' => 'App\\Voiture',
                    'client_id' => rand(0,9)
                ]);
            }
            for ($i=0; $i < sizeof($accessoires); $i++) {
                $voiture->accessoires()->save(App\Accessoire::find($i+1), ['quantité' => rand(0,2)]);
            }
            for ($i=0; $i < sizeof($documents); $i++) {
                $voiture->documents()->save(App\Document::find($i+1));
            }

        });

        // factory(App\Technicien::class, 3)->create();

        // factory(App\Maintenance::class, 100)->create()->each(function($maintenance) {
        //     factory(App\Panne::class, 10)->create([
        //         'maintenance_id' => $maintenance->id
        //     ]);
        // });



    }
}
