@extends('layouts.app')
@section('portal-module', 'installers')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', $installer->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('installers.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $installer->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('installers.edit', $installer) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('installers.destroy', $installer) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                <div class="module-record-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-diagram-2 me-2 text-primary"></i>{{ __('locale.lbl_installer_details') ?? 'Détails de l\'installateur' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $installer->name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.address') }}</dt>
                            <dd class="col-sm-8">
                                {{ $installer->address1 }}
                                @if($installer->address2) <br>{{ $installer->address2 }} @endif
                                <br>{{ $installer->postal_code }} {{ $installer->city }}
                                @if($installer->state) ({{ $installer->state }}) @endif
                                <br>{{ $installer->country }}
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.lbl_email') }}</dt>
                            <dd class="col-sm-8">
                                @if($installer->email)
                                    <a href="mailto:{{ $installer->email }}" class="text-decoration-none">{{ $installer->email }}</a>
                                @else
                                    &mdash;
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.lbl_phone') }}</dt>
                            <dd class="col-sm-8">{{ $installer->phone ?: '—' }}</dd>

                            @if($installer->fax)
                                <dt class="col-sm-4 text-muted">{{ __('locale.lbl_fax') }}</dt>
                                <dd class="col-sm-8">{{ $installer->fax }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
