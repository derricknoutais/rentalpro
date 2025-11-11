@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réservations à venir</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color:#0f172a; margin:0; padding:24px; background:#f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0;">
        <tr>
            <td style="padding:24px 32px;">
                <p style="margin:0 0 16px 0;">Bonjour,</p>
                <p style="margin:0 0 24px 0;">
                    Voici les réservations de <strong>{{ $compagnie->nom }}</strong> prévues dans les trois prochains jours.
                </p>

                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th align="left" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Réservation</th>
                            <th align="left" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Client</th>
                            <th align="left" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">Période</th>
                            <th align="right" style="padding:12px; font-size:13px; text-transform:uppercase; letter-spacing:0.05em; color:#475569; border-bottom:1px solid #e2e8f0;">J-?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $index => $reservation)
                            <tr style="background: {{ $index % 2 === 0 ? '#ffffff' : '#f8fafc' }};">
                                <td style="padding:12px; font-size:14px;">
                                    <strong>#{{ $reservation->id }}</strong><br>
                                    <span style="color:#64748b;">
                                        {{ optional($reservation->contractable)->immatriculation ?? optional($reservation->contractable)->nom ?? 'Contractable' }}
                                    </span><br>
                                    <a href="{{ $reservation->detail_url }}" style="color:#2563eb; text-decoration:none;">Consulter la réservation</a>
                                </td>
                                <td style="padding:12px; font-size:14px;">
                                    {{ optional($reservation->client)->nom }} {{ optional($reservation->client)->prenom }}<br>
                                    <span style="color:#94a3b8;">{{ optional($reservation->client)->phone1 }}</span>
                                </td>
                                <td style="padding:12px; font-size:14px;">
                                    {{ Carbon::parse($reservation->du)->locale('fr')->isoFormat('DD MMM HH:mm') }}<br>
                                    <span style="color:#94a3b8;">{{ Carbon::parse($reservation->au)->locale('fr')->isoFormat('DD MMM HH:mm') }}</span>
                                </td>
                                <td align="right" style="padding:12px; font-size:14px;"><strong>{{ max($reservation->days_until, 0) }}</strong> j</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p style="margin:32px 0 0 0; font-size:14px; color:#475569;">
                    Pensez à confirmer les disponibilités avec vos équipes ou à convertir ces réservations en contrat si nécessaire.
                </p>
                <p style="margin:12px 0 0 0; font-size:14px; color:#94a3b8;">— L'équipe RentalPro</p>
            </td>
        </tr>
    </table>
</body>

</html>
