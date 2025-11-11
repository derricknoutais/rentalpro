@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Documents véhicules à renouveler</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color:#111827; margin:0; padding:24px; background:#f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0;">
        <tr>
            <td style="padding:24px 32px;">
                <p style="margin:0 0 16px 0;">Bonjour,</p>
                <p style="margin:0 0 24px 0;">
                    Voici la liste des documents véhicules de <strong>{{ $compagnieName }}</strong> qui arrivent à
                    expiration dans les prochains jours.
                </p>

                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th align="left" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Véhicule</th>
                            <th align="left" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Document</th>
                            <th align="left" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Expiration</th>
                            <th align="right" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Jours restants</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $index => $document)
                            <tr style="background: {{ $index % 2 === 0 ? '#ffffff' : '#f8fafc' }};">
                                <td style="padding:12px; font-size:14px;">
                                    <strong>{{ $document->immatriculation }}</strong><br>
                                    <span style="color:#64748b;">{{ trim(($document->marque ?? '') . ' ' . ($document->type ?? '')) }}</span><br>
                                    <a href="{{ $document->voiture_url }}" style="color:#2563eb; text-decoration:none;">Voir le véhicule</a>
                                </td>
                                <td style="padding:12px; font-size:14px;">{{ $document->document_type }}</td>
                                <td style="padding:12px; font-size:14px;">
                                    {{ Carbon::parse($document->expiration_date)->locale('fr')->isoFormat('DD MMM YYYY') }}
                                </td>
                                <td align="right" style="padding:12px; font-size:14px;">
                                    <strong>{{ max($document->days_remaining, 0) }}</strong> j
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="margin:32px 0 0 0; font-size:14px; color:#475569;">
                    Pensez à renouveler ces documents avant leur date d'expiration afin d'éviter toute immobilisation.
                </p>
                <p style="margin:12px 0 0 0; font-size:14px; color:#94a3b8;">— L'équipe RentalPro</p>
            </td>
        </tr>
    </table>
</body>

</html>
