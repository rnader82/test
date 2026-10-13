@extends('layouts.app')

@section('title', __('locale.menu_companies'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0">{{ __('locale.menu_companies') }}</h1>
        <span class="header-count">({{ method_exists($companies, 'total') ? $companies->total() : $companies->count() }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('companies.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.create_company') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('locale.id') }}</th>
                                <th>{{ __('locale.lbl_company_code') }}</th>
                                <th>{{ __('locale.lbl_company_name') }}</th>
                                <th class="text-center" style="width: 100px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companies as $company)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('companies.show', $company->id) }}'">
                                    <td><span class="text-muted fw-semibold">#{{ $company->id }}</span></td>
                                    <td>
                                        <span class="badge bg-primary px-3 py-2 fw-bold">{{ $company->code }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('companies.show', $company->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $company->name }}
                                        </a>
                                    </td>
                                    <td class="text-center" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="companyActionMenu{{ $company->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="companyActionMenu{{ $company->id }}">
                                                <li>
                                                    <a href="{{ route('companies.show', $company->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('companies.edit', $company->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-warning"></i>{{ __('locale.lbl_edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
                                                            <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($companies, 'links') && $companies->hasPages())
                    <div class="card-footer d-flex justify-content-end border-top bg-transparent py-3">
                        {{ $companies->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
