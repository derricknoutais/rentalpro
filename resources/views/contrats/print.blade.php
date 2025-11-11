@extends('layouts.printA4')

@php
    use Illuminate\Support\Str;
@endphp

@section('print-toolbar')
    <div class="flex justify-end w-full">
        <pdf-download-button target="#printable-area"
            filename="facture-{{ Str::slug($contrat->numéro) }}"></pdf-download-button>
    </div>
@endsection

@section('print-content')
    @php
        $formatter = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);
        $total = (float) $contrat->total();
        $paid = (float) $contrat->payé();
        $balance = (float) $contrat->solde();
        $totalInWords = ucfirst($formatter->format(max(0, (int) round($total))));
        $contractable = optional($contrat->contractable);
        $client = optional($contrat->client);
        $compagnie = optional($contrat->compagnie);
        $logoInitials = Str::of($compagnie->nom ?? 'RP')
            ->trim()
            ->replaceMatches('/[^A-Z]/i', '')
            ->upper()
            ->substr(0, 2);

        $items = [
            [
                'label' => $contrat->compagnie->isVehicules()
                    ? 'Location ' . ($contractable->immatriculation ?? 'véhicule')
                    : 'Location ' . ($contractable->nom ?? 'hébergement'),
                'description' =>
                    $contrat->du && $contrat->au
                        ? 'Du ' . $contrat->du->format('d/m/Y H:i') . ' au ' . $contrat->au->format('d/m/Y H:i')
                        : 'Durée de ' . $contrat->nombre_jours . ' jour(s)',
                'unit' => $contrat->prix_journalier ?? 0,
                'quantity' => $contrat->nombre_jours,
                'total' => ($contrat->prix_journalier ?? 0) * $contrat->nombre_jours,
            ],
        ];

        if ($contrat->demi_journee) {
            $items[] = [
                'label' => 'Option demi-journée',
                'description' => 'Temps supplémentaire facturé en forfait',
                'unit' => $contrat->demi_journee,
                'quantity' => 1,
                'total' => $contrat->demi_journee,
            ];
        }

        if ($contrat->montant_chauffeur) {
            $items[] = [
                'label' => 'Service chauffeur',
                'description' => 'Assistance conducteur',
                'unit' => $contrat->montant_chauffeur,
                'quantity' => 1,
                'total' => $contrat->montant_chauffeur,
            ];
        }

        $vatRate = data_get($compagnie, 'vat_rate', 0);
        if (!is_numeric($vatRate)) {
            $vatRate = 0;
        }
        $pretaxTotal = $vatRate ? round($total / (1 + $vatRate), 2) : $total;
        $vatAmount = $total - $pretaxTotal;
    @endphp



    <section class="sheet">
        <div class="facture">
        <header class="facture__header avoid-page-break">
            <div class="facture__brand">
                <div class="facture__logo">{{ $logoInitials ?: 'RP' }}</div>
                <div>
                    <h1>{{ $compagnie->nom ?? config('app.name') }}</h1>
                    <p class="text-sm text-gray-500">{{ $compagnie->type ?? 'Entreprise' }}</p>
                </div>
            </div>
            <div class="facture__meta">
                <div class="facture__meta-title">FACTURE</div>
                <dl>
                    <div>
                        <dt>Référence</dt>
                        <dd>{{ $contrat->numéro }}</dd>
                    </div>
                    <div>
                        <dt>Date de facturation</dt>
                        <dd>{{ $contrat->created_at->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt>Référence client</dt>
                        <dd>{{ 'C-' . str_pad((string) $contrat->client_id, 5, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                </dl>
            </div>
        </header>

        <div class="facture__info-grid avoid-page-break">
            <section class="facture__info-card">
                <h3>{{ $compagnie->nom ?? 'Nom de votre entreprise' }}</h3>
                <div class="facture__contact">
                    <span>{{ $compagnie->adresse ?? 'Adresse non renseignée' }}</span>
                    <span>{{ $compagnie->ville ?? '' }} {{ $compagnie->pays ?? '' }}</span>
                    <span>Tél. : {{ $compagnie->phone ?? '—' }}</span>
                    <span>{{ $compagnie->email ?? '' }}</span>
                    @if (!empty($compagnie->site_web))
                        <span>{{ $compagnie->site_web }}</span>
                    @endif
                </div>
            </section>
            <section class="facture__info-card facture__info-card--highlight">
                <h3>Client</h3>
                <div class="facture__contact">
                    <span>{{ trim(($client->nom ?? '') . ' ' . ($client->prenom ?? '')) ?: 'Client' }}</span>
                    <span>{{ $client->adresse ?? 'Adresse non renseignée' }}</span>
                    <span>{{ $client->ville ?? '' }} {{ $client->pays ?? '' }}</span>
                    <span>Tél. : {{ $client->phone1 ?? '—' }}</span>
                    <span>{{ $client->mail ?? '' }}</span>
                </div>
            </section>
        </div>

        <section class="facture__table-wrapper avoid-page-break">
            <table class="facture__table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Prix unit.</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <p class="facture__desc">
                                    {{ $item['label'] }}
                                    @if ($item['description'])
                                        <small>{{ $item['description'] }}</small>
                                    @endif
                                </p>
                            </td>
                            <td>{{ number_format($item['unit'], 0, ',', ' ') }} FCFA</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['total'], 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <div class="facture__summary-grid avoid-page-break">
            <section class="facture__panel">
                <h4>Informations bancaires</h4>
                <dl>
                    <div>
                        <dt>Banque</dt>
                        <dd>{{ $compagnie->banque ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>RIB</dt>
                        <dd>{{ $compagnie->rib ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>IBAN</dt>
                        <dd>{{ $compagnie->iban ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>BIC</dt>
                        <dd>{{ $compagnie->bic ?? '—' }}</dd>
                    </div>
                </dl>
            </section>
            <section class="facture__panel">
                <h4>Projet global</h4>
                <div class="facture__totals">
                    <div class="facture__totals-row">
                        <span>Total HT</span>
                        <span>{{ number_format($pretaxTotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="facture__totals-row">
                        <span>TVA {{ $vatRate ? (int) ($vatRate * 100) . '%' : '' }}</span>
                        <span>{{ number_format($vatAmount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="facture__totals-row facture__totals-row--em">
                        <span>Total TTC</span>
                        <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="facture__totals-row">
                        <span>Déjà réglé</span>
                        <span>{{ number_format($paid, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="facture__totals-row facture__totals-row--em">
                        <span>Net à payer</span>
                        <span>{{ number_format($balance, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                <div class="mt-4 space-y-1 text-sm text-gray-500">
                    <p>Date d'échéance : {{ optional($contrat->au)->format('d/m/Y') ?? '—' }}</p>
                    <p>Mode de paiement : {{ $contrat->mode_paiement ?? 'Non précisé' }}</p>
                    <span class="facture__badge-soft">Prestation de service</span>
                </div>
            </section>
        </div>
        </div>
    </section>

    <section class="sheet sheet--last">
        <div class="facture facture--secondary">
        <div class="facture__summary-grid avoid-page-break">
            <section class="facture__panel">
                <h4>Documents du véhicule</h4>
                @if ($documents->count())
                    <ul class="invoice__list">
                        @foreach ($documents as $document)
                            <li>
                                <span>{{ $document->type }}</span>
                                <span class="invoice__pill">
                                    {{ $document->pivot->date_expiration ? \Carbon\Carbon::parse($document->pivot->date_expiration)->format('d/m/Y') : '—' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="invoice__hint">Aucun document rattaché.</p>
                @endif
            </section>
            <section class="facture__panel">
                <h4>Accessoires</h4>
                @if ($accessoires->count())
                    <ul class="invoice__list">
                        @foreach ($accessoires as $accessoire)
                            <li>
                                <span>{{ $accessoire->type }}</span>
                                <span class="invoice__pill">{{ $accessoire->pivot->quantité }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="invoice__hint">Aucun accessoire renseigné.</p>
                @endif
            </section>
        </div>

        <section class="facture__panel avoid-page-break">
            <h4>Conditions et mentions</h4>
            <ol class="invoice__terms">
                <li>Le véhicule doit être restitué à l'heure indiquée au contrat.</li>
                <li>Tout dommage non signalé sera facturé au locataire.</li>
                <li>Les photos jointes font office de référence pour l'état du véhicule.</li>
                <li>Retard de paiement : pénalité forfaitaire conformément au contrat.</li>
                <li>Conduite par un tiers non autorisé = résiliation immédiate.</li>
                <li>Prolongation à notifier 24h avant l'échéance.</li>
            </ol>
        </section>

        <div class="invoice__signature-grid avoid-page-break">
            <div class="invoice__signature">
                <p class="facture__panel-title" style="margin-bottom:0;">Signature client</p>
                @if ($contrat->checkout && $contrat->checkout->signature)
                    <img src="https://rentalpro.fra1.digitaloceanspaces.com/{{ $contrat->checkout->signature }}"
                        alt="Signature client">
                @else
                    <p class="invoice__hint">Signature à compléter</p>
                @endif
            </div>
            <div class="invoice__signature">
                <p class="facture__panel-title" style="margin-bottom:0;">Signature responsable</p>
                @if ($contrat->checkout && $contrat->checkout->userSignature)
                    <img src="https://rentalpro.fra1.digitaloceanspaces.com/{{ $contrat->checkout->userSignature }}"
                        alt="Signature responsable">
                @else
                    <p class="invoice__hint">Signature à compléter</p>
                @endif
            </div>
        </div>

        <footer class="invoice__footnote">
            Société {{ $compagnie->nom ?? config('app.name') }} — {{ $compagnie->forme_juridique ?? '' }} — SIRET
            {{ $compagnie->siret ?? 'N/A' }}<br>
            En cas de retard de paiement, des pénalités pourront être appliquées (Article L441-10 du Code de Commerce).
        </footer>
        </div>
    </section>
@endsection
