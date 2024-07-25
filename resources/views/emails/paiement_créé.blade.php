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

<body class="tw-bg-blue-100">

    <div class="tw-flex tw-justify-center">
        <img src="/img/logosta.png" alt="">
    </div>
    <h1 class="tw-text-4xl tw-mt-5 tw-text-center">Nouveau Contrat Créé</h1>
    <div class="tw-flex tw-flex-col tw-items-center tw-mt-1">
        {{-- CONTRAT --}}
        <div class="tw-items-center tw-bg-white tw-w-2/3 tw-rounded-md tw-p-3">
            <h2 class="tw-text-2xl">Contrat Nº {{ $contrat->numéro }}</h2>
            <div class="tw-grid tw-grid-cols-2 tw-w-2/3 tw-mt-3">

                <dd class="tw-text-lg tw-text-gray-400">Période du:</dd>
                <dt class="tw-text-lg">{{ $contrat->du->format('d-M-Y') }}</dt>

                <dd class="tw-text-lg tw-text-gray-400">Au:</dd>
                <dt class="tw-text-lg">{{ $contrat->au->format('d-M-Y') }}</dt>

                <dd class="tw-text-lg text-gray-400">Prix Journalier:</dd>
                <dt class="tw-text-lg">{{ number_format($contrat->prix_journalier, 0, ',', '.') }} F CFA</dt>

                <dd class="tw-text-lg tw-text-gray-400">Nombre de Jours:</dd>
                <dt class="tw-text-lg">{{ $contrat->nombre_jours }}</dt>

                <dd class="tw-text-lg tw-text-gray-400">Montant Total</dd>
                <dt class="tw-text-lg">{{ $contrat->total }}</dt>

            </div>
        </div>

        {{-- PAIEMENT --}}
        <div class="tw-bg-white tw-w-2/3 tw-rounded-md tw-p-3 tw-mt-3 ">
            <h3 class="tw-text-2xl tw-my-3">Paiement</h3>
            <div class="tw-grid tw-grid-cols-2 tw-w-2/3">
                <dt class="tw-text-lg tw-text-gray-400">Montant Versé:</dt>
                <dl class="tw-text-lg">{{ $paiement->montant }}</dl>

                <dt class="tw-text-lg tw-text-gray-400">Caution Versée:</dt>
                <dl class="tw-text-lg">{{ $contrat->caution }}</dl>



                <dt class="tw-text-lg tw-text-gray-400">Type Paiement</dt>
                <dl class="tw-text-lg">{{ $paiement->type_paiement }}</dl>

                <dt class="tw-text-lg tw-text-gray-400">Note</dt>
                <dl class="tw-text-lg">{{ $paiement->note }}</dl>
            </div>

        </div>




        <a target="_blank"
            class="tw-hover:no-underline tw-px-4 tw-bg-blue-500 tw-text-white tw-py-2 tw-rounded-sm tw-mt-12"
            href="https://rentalpro.azimuts.gq/contrat/{{ $contrat->id }}">
            Voir Contrat
        </a>
    </div>


</body>

</html>
