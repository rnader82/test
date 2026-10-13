<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte Rendu de Chantier - {{ $constructionReport->report_number }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            width: 150px;
        }
        .coordinates {
            text-align: right;
            font-size: 9pt;
        }
        .project-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .project-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .project-picture {
            width: 200px;
            height: auto;
            border: 1px solid #ddd;
        }
        .status-legend {
            margin-bottom: 15px;
            font-weight: bold;
        }
        .status-present { color: green; }
        .status-absent { color: orange; }
        .status-excuse { color: #00FFFF; } /* Blue Azur */
        .status-absent-non-excuse { color: red; }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-size: 9pt;
        }
        .section-title {
            background-color: #333;
            color: #fff;
            padding: 5px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        .remark-image {
            max-width: 150px;
            margin: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    @if($constructionReport->logo_path)
                        <img src="{{ public_path('storage/' . $constructionReport->logo_path) }}" class="logo">
                    @else
                        {{-- Fallback logo --}}
                        <div style="font-size: 18pt; font-weight: bold;">LOGO</div>
                    @endif
                </td>
                <td class="coordinates">
                    {!! nl2br(e($constructionReport->coordinates)) !!}
                </td>
            </tr>
        </table>
    </div>

    <div class="project-info">
        <table>
            <tr>
                <td width="220">
                    @if($constructionReport->project_picture_path)
                        <img src="{{ public_path('storage/' . $constructionReport->project_picture_path) }}" class="project-picture">
                    @endif
                </td>
                <td>
                    <h1 style="margin-top: 0;">{{ $constructionReport->project->name ?? 'Projet sans nom' }}</h1>
                    <p>
                        <strong>Client :</strong> {{ $constructionReport->client->raison_sociale ?? 'N/A' }}<br>
                    </p>
                    <p>
                        <strong>Compte Rendu n° :</strong> {{ $constructionReport->report_number }}<br>
                        <strong>Date :</strong> {{ $constructionReport->date->format('d/m/Y') }}<br>
                        <strong>Lieu :</strong> {{ $constructionReport->location }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="status-legend">
        Statuts : 
        <span class="status-present">Présent</span> | 
        <span class="status-absent">Absent</span> | 
        <span class="status-excuse">Excusé</span> | 
        <span class="status-absent-non-excuse">Absent non excusé</span>
    </div>

    <h3 class="section-title">Participants</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Fonction / Position</th>
                <th>Société</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Statut</th>
                <th width="30" title="Convoqué">C</th>
                <th width="30" title="Nombre d'absences">N</th>
                <th width="30" title="Recevoir le rapport par email">R</th>
            </tr>
        </thead>
        <tbody>
            @forelse($constructionReport->stakeholders as $stakeholder)
                <tr>
                    <td>{{ $stakeholder->first_name }}</td>
                    <td>{{ $stakeholder->last_name }}</td>
                    <td>{{ $stakeholder->position }}</td>
                    <td>{{ $stakeholder->company }}</td>
                    <td>{{ $stakeholder->phone }}</td>
                    <td>{{ $stakeholder->email }}</td>
                    <td>
                        @php
                            $statusClass = match($stakeholder->status) {
                                'present' => 'status-present',
                                'absent' => 'status-absent',
                                'excuse' => 'status-excuse',
                                'absent_non_excuse' => 'status-absent-non-excuse',
                                default => ''
                            };
                            $statusLabel = match($stakeholder->status) {
                                'present' => 'Présent',
                                'absent' => 'Absent',
                                'excuse' => 'Excusé',
                                'absent_non_excuse' => 'Absent non excusé',
                                default => $stakeholder->status
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td align="center">{{ $stakeholder->is_convoque ? 'X' : '' }}</td>
                    <td align="center">{{ $stakeholder->absence_count }}</td>
                    <td align="center">{{ $stakeholder->receives_report ? 'X' : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" align="center">Aucun participant</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3 class="section-title">Observations</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="50">N°</th>
                <th width="150">Titre</th>
                <th>Description</th>
                <th width="80">Créé le</th>
                <th width="80">Date limite</th>
                <th width="80">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($constructionReport->remarks as $remark)
                <tr>
                    <td>{{ $remark->number }}</td>
                    <td>{{ $remark->title }}</td>
                    <td>
                        {!! $remark->description !!}
                        @if($remark->images->count() > 0)
                            <div style="margin-top: 10px;">
                                @foreach($remark->images as $image)
                                    <img src="{{ public_path('storage/' . $image->image_path) }}" class="remark-image">
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td>{{ $remark->created_date ? $remark->created_date->format('d/m/Y') : '' }}</td>
                    <td>{{ $remark->limit_date ? $remark->limit_date->format('d/m/Y') : '' }}</td>
                    <td>{{ $remark->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center">Aucune observation</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
