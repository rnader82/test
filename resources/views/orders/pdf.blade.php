<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devis {{ $order->ref_interne ?: ($order->numero ?: $order->id) }}</title>
    <style>
        @page {
            margin: 15mm 15mm 20mm 15mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-banner {
            background-color: #008ba3;
            color: #ffffff;
            padding: 18px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .header-top {
            width: 100%;
            margin-bottom: 15px;
        }
        .header-top td {
            vertical-align: middle;
        }
        .logo-title {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .logo-subtitle {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #b2ebf2;
            margin-top: 2px;
        }
        .header-contact {
            text-align: right;
            font-size: 10px;
            color: #e0f7fa;
            line-height: 1.5;
        }
        .header-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-info-table td {
            vertical-align: top;
            padding: 4px 6px;
        }
        .client-box {
            width: 38%;
            font-size: 10px;
            line-height: 1.4;
        }
        .client-box strong {
            font-size: 12px;
        }
        .commercial-box {
            width: 32%;
            font-size: 10px;
            line-height: 1.5;
        }
        .quote-ref-box {
            width: 30%;
            background-color: #005b6e;
            color: #ffffff;
            padding: 10px 12px;
            border-radius: 4px;
            text-align: center;
        }
        .quote-simul {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .quote-num {
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .quote-meta {
            font-size: 9px;
            color: #e0f7fa;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 5px;
            margin-top: 5px;
        }
        
        /* Table of Lines */
        .lines-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .lines-table th {
            background-color: #90a4ae;
            color: #ffffff;
            text-align: left;
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .lines-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #eceff1;
            vertical-align: top;
        }
        .lines-table tr.chapter-row td {
            background-color: #ffe0b2;
            color: #d84315;
            font-weight: bold;
            font-size: 11px;
            padding: 8px 10px;
            border-bottom: 2px solid #ffcc80;
        }
        .lines-table tr.section-row td {
            background-color: #f5f5f5;
            color: #455a64;
            font-weight: bold;
            font-size: 10px;
            padding: 6px 10px;
            border-bottom: 1px solid #cfd8dc;
        }
        .lines-table tr.option-row td {
            background-color: #f1f8e9;
            color: #33691e;
        }
        .lines-table tr.comment-row td {
            background-color: #f9fbe7;
            font-style: italic;
            color: #555555;
        }
        .col-ref { width: 22%; font-weight: bold; }
        .col-desc { width: 44%; }
        .col-qty { width: 10%; text-align: center; }
        .col-pu { width: 12%; text-align: right; }
        .col-tot { width: 12%; text-align: right; font-weight: bold; }

        /* Totals Block */
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .totals-table td {
            padding: 5px 8px;
            font-size: 11px;
        }
        .totals-table tr.total-ttc td {
            font-size: 13px;
            font-weight: bold;
            color: #008ba3;
            border-top: 2px solid #008ba3;
            padding-top: 8px;
        }

        /* Reserves / Conditions footer */
        .conditions-block {
            margin-top: 25px;
            padding: 12px 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 9.5px;
            line-height: 1.5;
            color: #475569;
        }
        .conditions-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        
        .footer-note {
            margin-top: 30px;
            text-align: center;
            font-size: 8.5px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header Banner -->
    <div class="header-banner">
        <table class="header-top">
            <tr>
                <td>
                    <div class="logo-title">⊕ novalyo</div>
                    <div class="logo-subtitle">Intelligences Connectées Santé</div>
                </td>
                <td class="header-contact">
                    commande@novalyo.fr<br>
                    www.novalyo.fr
                </td>
            </tr>
        </table>

        <table class="header-info-table">
            <tr>
                <td class="client-box">
                    <div style="font-size: 9px; text-transform: uppercase; color: #b2ebf2; margin-bottom: 3px;">Devis pour :</div>
                    <strong>{{ optional($order->client)->raison_sociale ?? 'CLIENT' }}</strong><br>
                    @if($order->contact)
                        M/Mme {{ $order->contact->prenom }} {{ $order->contact->nom }}<br>
                    @endif
                    {{ optional($order->client)->adresse_1 }}<br>
                    {{ optional($order->client)->code_postal }} {{ optional($order->client)->ville }}
                </td>
                <td class="commercial-box">
                    <div style="font-size: 9px; text-transform: uppercase; color: #b2ebf2; margin-bottom: 3px;">Contacts :</div>
                    Contact commercial : <strong>{{ optional($order->commercial)->name ?? (auth()->user()->name ?? 'Mohamed KHELOUFI') }}</strong><br>
                    Rédacteur de l'offre : <strong>{{ auth()->user()->name ?? 'Ralph NADER' }}</strong><br>
                    @if($order->validity_date)
                        Valide jusqu'au : <strong>{{ $order->validity_date->format('d/m/Y') }}</strong>
                    @endif
                </td>
                <td class="quote-ref-box">
                    <div class="quote-simul">Simulation Non Contractuelle</div>
                    <div class="quote-num">N° {{ $order->ref_interne ?: ($order->numero ?: ('B' . $order->id . '-1')) }}</div>
                    <div class="quote-meta">
                        No Client: <strong>{{ optional($order->client)->cegid_id ?: ('C' . str_pad($order->client_id ?? 1, 8, '0', STR_PAD_LEFT)) }}</strong> &nbsp;|&nbsp; 
                        Le: <strong>{{ $order->date_piece ? $order->date_piece->format('d/m/Y') : date('d/m/Y') }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Lines Table -->
    <table class="lines-table">
        <thead>
            <tr>
                <th class="col-ref">Référence</th>
                <th class="col-desc">Désignation</th>
                <th class="col-qty">Quantité</th>
                <th class="col-pu">Prix unitaire €</th>
                <th class="col-tot">Prix total €</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentChapter = null;
                $lines = $order->lines ?? collect();
            @endphp
            @forelse($lines as $line)
                @if($line->type_ligne === 'Chapitre' || $line->type_ligne === 'chapter')
                    <tr class="chapter-row">
                        <td colspan="4">
                            📁 {{ $line->chapter_title ?: ($line->libelle ?: 'Chapitre') }}
                        </td>
                        <td class="col-tot">
                            <!-- Chapter Subtotal -->
                        </td>
                    </tr>
                @elseif($line->type_ligne === 'Section' || $line->type_ligne === 'section')
                    <tr class="section-row">
                        <td colspan="5">
                            🗐 {{ $line->libelle }}
                        </td>
                    </tr>
                @elseif($line->type_ligne === 'Commentaire' || $line->type_ligne === 'comment')
                    <tr class="comment-row">
                        <td colspan="5">
                            💬 {{ $line->libelle }}
                        </td>
                    </tr>
                @else
                    <tr class="{{ $line->is_option ? 'option-row' : '' }}">
                        <td class="col-ref">
                            {{ $line->code_article }}
                            @if($line->is_option)
                                <span style="font-size: 8px; background: #e8f5e9; color: #2e7d32; padding: 1px 4px; border-radius: 2px;">Option</span>
                            @endif
                        </td>
                        <td class="col-desc">
                            {{ $line->libelle }}
                        </td>
                        <td class="col-qty">
                            {{ number_format((float) $line->qte, 0, ',', ' ') }}
                        </td>
                        <td class="col-pu">
                            {{ number_format((float) ($line->pu_ht_net ?? $line->pu_ht), 2, ',', ' ') }} €
                        </td>
                        <td class="col-tot">
                            {{ number_format((float) $line->montant_ht, 2, ',', ' ') }} €
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #999; padding: 20px;">
                        Aucun article dans ce devis.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Totals Table -->
    <table class="totals-table">
        <tr>
            <td style="color: #64748b;">Total HT brut :</td>
            <td style="text-align: right; font-weight: bold;">{{ number_format((float) ($order->total_ht + ($order->remise_pied ?? 0)), 2, ',', ' ') }} €</td>
        </tr>
        @if(($order->remise_pied ?? 0) > 0)
            <tr>
                <td style="color: #e11d48;">Remise globale :</td>
                <td style="text-align: right; color: #e11d48; font-weight: bold;">- {{ number_format((float) $order->remise_pied, 2, ',', ' ') }} €</td>
            </tr>
        @endif
        <tr>
            <td style="color: #0f172a; font-weight: bold;">Total Net HT :</td>
            <td style="text-align: right; font-weight: bold; font-size: 12px;">{{ number_format((float) $order->total_ht, 2, ',', ' ') }} €</td>
        </tr>
        <tr>
            <td style="color: #64748b;">T.V.A. (20,00%) :</td>
            <td style="text-align: right;">{{ number_format((float) ($order->total_ht * 0.20), 2, ',', ' ') }} €</td>
        </tr>
        <tr class="total-ttc">
            <td>TOTAL TTC :</td>
            <td style="text-align: right;">{{ number_format((float) ($order->total_ttc ?? ($order->total_ht * 1.20)), 2, ',', ' ') }} €</td>
        </tr>
    </table>

    <!-- Conditions & Reserves -->
    @if(!empty($order->reserves) || $order->has_tech_validation || $order->has_commercial_validation)
        <div class="conditions-block">
            <div class="conditions-title">Conditions spécifiques & Réserves</div>
            @if(!empty($order->reserves))
                @php
                    $resList = is_string($order->reserves) ? explode("\n", $order->reserves) : (array) $order->reserves;
                @endphp
                <ul style="margin: 0 0 6px 15px; padding: 0;">
                    @foreach($resList as $res)
                        @if(trim($res))
                            <li>{{ trim($res) }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
            @if($order->has_tech_validation)
                <div>• Soumis à validation technique préalable.</div>
            @endif
            @if($order->has_commercial_validation)
                <div>• Soumis à validation commerciale préalable.</div>
            @endif
        </div>
    @endif

    <!-- Footer Note -->
    <div class="footer-note">
        Novalyo SAS au capital de 100 000 € — RCS Vienne — SIRET 819 254 100 00012 — APE 6190Z<br>
        Siège social : 38150 Salaise-sur-Sanne — Tél : 04 74 29 00 00
    </div>

</body>
</html>
