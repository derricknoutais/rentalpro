<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facture</title>
    <script src="{{ asset('js/app.js') }}" defer></script>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

        <img src="{{ asset('/img/orishainn_logo.png') }}" style="width: 25rem" >
        <p style="margin-top: 0rem">MOUKALA, B.P:1268 PORT-GENTIL (GABON) </p>
        <p style="margin-top: 0rem">Téléphone: 011-56-08-55 / 077-59-92-90 / 066-88-77- </p>
        <h3 class="text-red-100 text-size-md" style="margin-top: 7rem">Facture Nº {{ $contrat->numéro }}</h3>

        <h6 class="my-4">Client: {{ $contrat->client->nom . ' ' . $contrat->client->prenom }}</h6>
        <p class="">Du {{ $contrat->du }} Au {{ $contrat->au }}</p>
        <table class="table" style="margin-top: 7rem; font-size:10px;">
            <thead>
                <tr>
                    <th>Nb.Jours</th>
                    <th>Nº Chambre</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row">{{ $contrat->nombre_jours }}</td>
                    <td>{{ $contrat->contractable->nom }}</td>
                    <td>{{ $contrat->prix_journalier }}</td>
                    <td>{{ $contrat->nombre_jours * $contrat->prix_journalier }} F CFA</td>
                </tr>
                <tr>
                    <td scope="row" colspan="2"></td>
                    @if ( sizeof($contrat->paiements) == 1 )
                        <td>{{ sizeof($contrat->paiements) }} Paiement Reçu </td>
                    @else
                        <td>{{ sizeof($contrat->paiements) }} Paiements Reçus </td>

                    @endif
                    <td>{{ $contrat->payé() }} F CFA</td>
                </tr>
                <tr>
                    <td scope="row" colspan="2"></td>
                    <td>Solde </td>
                    <td>{{ $contrat->nombre_jours * $contrat->prix_journalier - $contrat->payé() }} F CFA</td>
                </tr>
            </tbody>
        </table>
        <p style="text-decoration: underline; margin-top:4rem" >Arrêté la Présente Facture à la Somme de:</p>
        <p id="wordnumber">{{ $total_in_words }} F CFA</p>

        <p style="text-align:right;">Le Responsable</p>

</body>
</html>
