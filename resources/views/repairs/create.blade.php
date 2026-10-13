@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.create_repair') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <button type="submit" form="repairCreateForm" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> {{ __('locale.save') }}
        </button>
        <a href="{{ route('repairs.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-lg me-1"></i> {{ __('locale.cancel') }}
        </a>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('libs/flatpickr/flatpickr.css') }}">
@endpush

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('repairs.store') }}" method="POST" id="repairCreateForm" data-repair-form>
                @csrf

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
