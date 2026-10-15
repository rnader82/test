@extends('layouts.app')
@section('portal-module', 'repairs')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('repairs.show', $repair->id) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.edit_repair') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <button type="submit" form="repairEditForm" class="btn btn-warning">
            <i class="bi bi-check-lg me-1"></i> {{ __('locale.update') }}
        </button>
        <a href="{{ route('repairs.show', $repair->id) }}" class="btn btn-secondary">
            <i class="bi bi-x-lg me-1"></i> {{ __('locale.cancel') }}
        </a>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('libs/flatpickr/flatpickr.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="module-form-card card shadow-lg">
        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
            <h5 class="mb-0 fw-bold d-flex align-items-center">
                <i class="bi bi-pencil-square text-primary me-2 fs-4"></i> {{ __('locale.lbl_general_info') }}
            </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('repairs.update', $repair->id) }}" method="POST" id="repairEditForm" data-repair-form>
                @csrf
                @method('PUT')

                @include('repairs.form', [
                    'repair' => $repair,
                    'issues' => $issues,
                    'statuses' => $statuses,
                    'equipmentTypes' => $equipmentTypes,
                    'brands' => $brands,
                    'equipments' => $equipments,
                    'suppliers' => $suppliers ?? null,
                    'warrantyTypes' => $warrantyTypes ?? null,
                    'repairDiagnoses' => $repairDiagnoses ?? null,
                ])
            </form>
        </div>
    </div>
</div>

@include('repairs.partials.quick_issue_modal', [
    'issueClients' => $issueClients,
    'issueOrigines' => $issueOrigines,
    'issueCategories' => $issueCategories,
    'issueTypeOptions' => $issueTypeOptions,
    'issueCriticityOptions' => $issueCriticityOptions,
])

@include('repairs.partials.equipment_search_modal')

<script src="{{ asset('libs/flatpickr/flatpickr.js') }}"></script>
<!-- JavaScript to Handle Equipment Selection -->
@include('repairs.equipment_js')
@include('repairs.autocomplete_js')
@include('repairs.quick_issue_js')
@endsection
