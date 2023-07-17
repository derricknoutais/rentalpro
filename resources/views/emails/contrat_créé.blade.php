<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app2.css') }}" rel="stylesheet">
    <title>Document</title>
</head>

<body class="bg-blue-100">

    <div class="flex justify-center">
        <img src="/img/logosta.png" alt="">
    </div>
    <h1 class="text-4xl mt-5 text-center">Nouveau Contrat Créé</h1>
    <div class="flex flex-col items-center mt-1">
        {{-- CONTRAT --}}
        <div class="items-center bg-white w-1/3 rounded-md p-3">
            <h2 class="text-2xl">Contrat Nº {{ $contrat->numéro }}</h2>
            <div class="grid grid-cols-2 w-2/3 mt-3">

                <dd class="text-lg text-gray-400">Période du:</dd>
                <dt class="text-lg">{{ $contrat->du->format('d-M-Y') }}</dt>

                <dd class="text-lg text-gray-400">Au:</dd>
                <dt class="text-lg">{{ $contrat->au->format('d-M-Y') }}</dt>

                <dd class="text-lg text-gray-400">Prix Journalier:</dd>
                <dt class="text-lg">{{ number_format($contrat->prix_journalier, 0, ',', '.') }} F CFA</dt>

                <dd class="text-lg text-gray-400">Nombre de Jours:</dd>
                <dt class="text-lg">{{ $contrat->nombre_jours }}</dt>

                <dd class="text-lg text-gray-400">Montant Total</dd>
                <dt class="text-lg">{{ $contrat->total }}</dt>

            </div>
        </div>
        {{-- CLIENT --}}
        <div class="bg-white w-1/3 rounded-md p-3 mt-3 ">
            <h3 class="text-2xl my-3">Client</h3>
            <div class="grid grid-cols-2 w-2/3">
                <dt class="text-lg text-gray-400">Nom & Prénom:</dt>
                <dl class="text-lg">{{ $contrat->client->nom . ' ' . $contrat->client->prenom }}</dl>



                <dt class="text-lg text-gray-400">Nº Téléphone</dt>
                <dl class="text-lg">
                    <span>{{ $contrat->client->phone1 }}</span>
                    @if ($contrat->client->phone2)
                        <span>
                            / {{ $contrat->client->phone2 }}
                        </span>
                    @endif
                </dl>

                <dt class="text-lg text-gray-400">Addresse: </dt>
                <dl class="text-lg">{{ $contrat->client->addresse }}</dl>

            </div>

        </div>
        {{-- CONTRACTABLE --}}
        <div class="bg-white w-1/3 rounded-md p-3 mt-3 ">
            <h3 class="text-2xl my-3">Contractable</h3>
            <div class="grid grid-cols-2 w-2/3">
                <dt class="text-lg text-gray-400">Immatriculation :</dt>
                <dl class="text-lg">{{ $contrat->contractable->immatriculation }}</dl>



                <dt class="text-lg text-gray-400">Marque</dt>
                <dl class="text-lg">{{ $contrat->contractable->marque }}</dl>

                <dt class="text-lg text-gray-400">Type</dt>
                <dl class="text-lg">{{ $contrat->contractable->type }}</dl>
            </div>

        </div>
        <a target="_blank" class="hover:no-underline px-4 bg-blue-500 text-white py-2 rounded-sm mt-12"
            href="https://rentalpro.azimuts.gq/contrat/{{ $contrat->id }}">
            Voir Contrat
        </a>
    </div>


</body>

</html>
