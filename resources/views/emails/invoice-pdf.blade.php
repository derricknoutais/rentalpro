<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $contrat->numéro }}</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color:#0f172a; margin:0; padding:24px; background:#f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:12px; border:1px solid #e2e8f0;">
        <tr>
            <td style="padding:24px 32px;">
                <p style="margin:0 0 16px 0;">Bonjour {{ optional($contrat->client)->nom }},</p>
                <p style="margin:0 0 16px 0;">
                    Vous trouverez en pièce jointe la facture <strong>#{{ $contrat->numéro }}</strong> relative à votre contrat de location
                    pour {{ optional($contrat->contractable)->immatriculation ?? optional($contrat->contractable)->nom }}.
                </p>
                <p style="margin:0 0 16px 0;">
                    Période :
                    <strong>{{ optional($contrat->du)->format('d/m/Y H:i') ?? '—' }}</strong> au
                    <strong>{{ optional($contrat->au)->format('d/m/Y H:i') ?? '—' }}</strong>.
                </p>
                <p style="margin:0 0 24px 0;">
                    Total dû : <strong>{{ number_format($contrat->total(), 0, ',', ' ') }} FCFA</strong>.
                </p>
                <p style="margin:0;">Merci pour votre confiance.</p>
                <p style="margin:12px 0 0 0; color:#94a3b8;">— {{ optional($contrat->compagnie)->nom }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
