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
    <h1 class="text-4xl mt-5 text-center">RAPPORT PAIEMENTS</h1>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto mt-12">
                <h2 class="text-md font-semibold text-gray-900">Paiements du
                    {{ Carbon\Carbon::today()->subDay(1)->format('d-m-Y') }} 18:01 au
                    {{ Carbon\Carbon::today()->format('d-m-Y') }} 18:00</h2>
            </div>
        </div>
        <div class="-mx-4 mt-8 sm:-mx-0">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                            Date</th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                            Type
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                            Nº Contrat</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                            Immatriculation
                        </th>
                        <th scope="col"
                            class=" px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                            Montant
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($paiements_espece as $paiement)
                        <tr>
                            <td
                                class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                                {{ $paiement->created_at->format('h:i') }}
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                {{ $paiement->type_paiement }}
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->numéro }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->contractable->immatriculation }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ $paiement->montant }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"
                            class="w-full max-w-0 py-4 pl-4 pr-3 text-right text-md font-bold text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                            TOTAL ESPECE
                        </td>

                        <td class="hidden font-bold px-3 py-4 text-md text-gray-500 lg:table-cell">
                            {{ $total_espece = $paiements_espece->sum('montant') }}</td>
                    </tr>
                    <br>
                    @foreach ($paiements_airtelmoney as $paiement)
                        <tr class="mt-5">
                            <td
                                class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                                {{ $paiement->created_at->format('h:i') }}

                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                                {{ $paiement->type_paiement }}
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->numéro }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                                <span>{{ $paiement->payable->contractable->immatriculation }}</span>
                            </td>
                            <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">{{ $paiement->montant }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"
                            class="w-full max-w-0 py-4 pl-4 pr-3 text-right  text-md font-bold text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                            TOTAL AIRTEL MONEY
                        </td>

                        <td class="hidden px-3 py-4 font-exbold text-md text-gray-500 lg:table-cell">
                            {{ $total_airtelmoney = $paiements_airtelmoney->sum('montant') }}</td>
                    </tr>
                    <br>
                    <tr class="mt-5">
                        <td colspan="4"
                            class="w-full max-w-0 py-4 pl-4 pr-3 text-right  text-lg font-bold text-gray-900 sm:w-auto sm:max-w-none sm:pl-0">
                            TOTAL JOURNALIER
                        </td>

                        <td class="hidden px-3 py-4 font-extrabold text-lg text-gray-500 lg:table-cell">
                            {{ $total_espece + $total_airtelmoney }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


</body>

</html>
