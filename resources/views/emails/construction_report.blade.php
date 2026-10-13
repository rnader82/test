<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Compte Rendu de Chantier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f8fafc;
        }
        h2 {
            color: #91378e;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.875rem;
            color: #64748b;
            border-top: 1px solid #cbd5e1;
            padding-top: 15px;
        }
    /* Novalyo document palette; keep PDF/email layout self-contained. */
        h1, h2, h3 { color: #7e3e7b; }
        a { color: #91378e; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Compte Rendu de Chantier</h2>
        <p>Bonjour,</p>
        <p>Veuillez trouver ci-joint le compte rendu de chantier n° <strong>{{ $report->report_number }}</strong> en date du <strong>{{ $report->date ? $report->date->format('d/m/Y') : '' }}</strong>.</p>
        
        <p><strong>Détails :</strong></p>
        <ul>
            <li><strong>Projet :</strong> {{ $report->project->name ?? 'N/A' }}</li>
            <li><strong>Client :</strong> {{ $report->client->raison_sociale ?? 'N/A' }}</li>
            @if($report->location)
                <li><strong>Lieu :</strong> {{ $report->location }}</li>
            @endif
        </ul>

        <p>Ce document est joint au format PDF à ce courriel.</p>

        <p>Cordialement,</p>
        <p>L'équipe <strong>Novalyo 360</strong></p>

        <div class="footer">
            Ceci est un message automatique, veuillez ne pas y répondre directement.
        </div>
    </div>
</body>
</html>
