<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Nouveau Contrat Créé</h1>
    <h4>Numéro Contrat: {{ $contrat->numéro }}</h4>
    <h4>Client : {{ $contrat->client->nom . ' ' . $contrat->client->prenom  }}</h4>
    <h4>Nº de téléphone : {{ $contrat->client->phone1 }}</h4>
    <h4>Immatriculation : {{ $contrat->voiture->immatriculation }}</h4>
    <h4>Période du : {{ $contrat->au }} au : {{ $contrat->du }} </h4>
    <h4>Montant : {{ $contrat->prix_journalier }} x {{ $contrat->nombre_jours }} = {{ $contrat->total }}</h4>
    <a href="https://location.stapog.ga/contrat/{{ $contrat->id }}">
        Voir Contrat
    </a>
</body>
</html>
