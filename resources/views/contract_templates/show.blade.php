@extends('layouts.app')

@section('title', $contractTemplate->contract_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $contractTemplate->contract_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_templates.edit', $contractTemplate) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('contract_templates.destroy', $contractTemplate) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 fs-12">
                <i class="bi bi-trash me-1"></i>{{ __('locale.delete') }}
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-journal-text me-2 text-primary"></i>{{ __('locale.lbl_contract_template_details') ?? 'Détails du modèle de contrat' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') ?? 'Nom du contrat' }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $contractTemplate->contract_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.nb_vps_per_year') ?? 'Nb VPs / an' }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $contractTemplate->nb_vps_per_year }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.preavis_mois') ?? 'Préavis (mois)' }}</dt>
                            <dd class="col-sm-8">{{ $contractTemplate->preavis_en_mois }} mois</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.duree_renouvellement') ?? 'Durée renouvellement' }}</dt>
                            <dd class="col-sm-8">{{ $contractTemplate->duree_renouvellement_en_mois ? $contractTemplate->duree_renouvellement_en_mois . ' mois' : '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.nb_recurrences') ?? 'Nb de récurrences' }}</dt>
                            <dd class="col-sm-8">{{ $contractTemplate->nb_de_recurrences }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.tacite_reconduction') ?? 'Tacite reconduction' }}</dt>
                            <dd class="col-sm-8">{{ $contractTemplate->tacite_reconduction ? 'Oui' : 'Non' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.astreinte') ?? 'Astreinte' }}</dt>
                            <dd class="col-sm-8">{{ $contractTemplate->astreinte ? 'Oui' : 'Non' }}</dd>

                            @if($contractTemplate->divers)
                                <dt class="col-sm-4 text-muted">{{ __('locale.divers') ?? 'Divers' }}</dt>
                                <dd class="col-sm-8">{{ $contractTemplate->divers }}</dd>
                            @endif

                            @if($contractTemplate->presetClauses && $contractTemplate->presetClauses->count() > 0)
                                <dt class="col-sm-4 text-muted">{{ __('locale.preset_clauses') ?? 'Clauses prédéfinies' }} ({{ $contractTemplate->presetClauses->count() }})</dt>
                                <dd class="col-sm-8">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($contractTemplate->presetClauses as $pc)
                                            <li class="mb-1">
                                                <i class="bi bi-card-checklist me-1 text-primary"></i>
                                                {{ $pc->contractClauseTemplate->title ?? 'Clause #' . $pc->contract_clause_template_id }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            @endif

                            @if($contractTemplate->equipmentTypes && $contractTemplate->equipmentTypes->count() > 0)
                                <dt class="col-sm-4 text-muted">{{ __('locale.equipment_types') ?? 'Types d\'équipements' }} ({{ $contractTemplate->equipmentTypes->count() }})</dt>
                                <dd class="col-sm-8">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($contractTemplate->equipmentTypes as $et)
                                            <span class="badge bg-light text-dark border">
                                                {{ $et->equipmentType->name ?? 'Type #' . $et->equipment_type_id }}
                                            </span>
                                        @endforeach
                                    </div>
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
