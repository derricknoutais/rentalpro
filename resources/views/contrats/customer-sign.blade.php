@extends('layouts.app')

@section('content')
    <div class="">

        <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md">
            <h1 class="text-2xl font-bold mb-6 text-center">📄 Conditions Générales – Location de Voiture (Sans Caution)</h1>

            <div class="space-y-6">
                <div>
                    <h2 class="font-semibold text-lg">1. Objet du contrat</h2>
                    <p>Le présent contrat concerne la location d’un véhicule de type <span
                            class="italic">{{ $contrat->contractable->marque . ' ' . $contrat->contractable->type }}</span>,
                        immatriculé <span class="italic">{{ $contrat->contractable->immatriculation }}</span>, appartenant à
                        <span class="italic">Services Tous Azimuts</span>.
                    </p>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">2. Durée de la location</h2>
                    <p>La location est valable du <span class="italic">{{ $contrat->du }}</span> au <span
                            class="italic">{{ $contrat->au }}</span>. Toute prolongation devra être validée au préalable
                        par le loueur.</p>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">3. Tarifs et paiement</h2>
                    <p>Le tarif est fixé à <span class="italic">{{ $contrat->prix_journalier }} F CFA</span>, payable <span
                            class="italic">[à l’avance / en totalité à la remise du véhicule]</span>.
                        <strong>Aucune caution n’est demandée.</strong>
                        Toute journée entamée est due.
                    </p>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">4. Conditions d’usage</h2>
                    <ul class="list-disc list-inside">
                        <li>Le véhicule est remis propre et en bon état de marche.</li>
                        <li>Le locataire s’engage à :</li>
                        <ul class="list-disc list-inside ml-6">
                            <li>Ne pas prêter ou sous-louer le véhicule</li>
                            <li>Ne pas sortir de Port-Gentil sans autorisation écrite</li>
                            <li>Respecter le code de la route</li>
                            <li>Refaire le plein avant restitution</li>
                        </ul>
                    </ul>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">5. Assurance et responsabilité</h2>
                    <p>En cas d’accident, de vol ou de dommages, le locataire est responsable du montant non couvert par
                        l’assurance.
                    </p>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">6. Restitution</h2>
                    <p>Le véhicule doit être restitué à la date convenue, propre, et avec le même niveau de carburant. Des
                        frais seront facturés en cas de :</p>
                    <ul class="list-disc list-inside ml-6">
                        <li>Retard</li>
                        <li>Véhicule sale ou endommagé</li>
                        <li>Carburant manquant</li>
                    </ul>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">7. Annulation / non-présentation</h2>
                    <p>Toute annulation doit être signalée au moins 24h à l’avance. En cas de non-présentation sans préavis,
                        <span class="italic">[xx €]</span> seront facturés au titre de pénalité.
                    </p>
                </div>

                <div>
                    <h2 class="font-semibold text-lg">8. Litiges</h2>
                    <p>En cas de désaccord, les deux parties s’engagent à chercher une solution amiable. Si cela échoue, le
                        litige sera porté devant les autorités compétentes</p>
                </div>
            </div>
            <div class="flex flex-col px-6 py-12">
                <p>Le Client</p>
                <div class="w-full" style="width: 300px;">
                    <my-signature-pad contrat_id="{{ $contrat->id }}">

                    </my-signature-pad>
                </div>

            </div>
        </div>


    </div>
@endsection
