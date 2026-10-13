@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_report_intervention') }}</h1>
    </div>
@endsection

@section('content')
<div class="container-xxl mt-4">

        <!-- Filters Card -->
        <div class="card mb-4 card-premium shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>{{ __('locale.advanced_filters') }}</h5>
                <a href="{{ route('reports.intervention') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>{{ __('locale.reset') }}
                </a>
            </div>
            <div class="card-body pt-4">
                <form method="GET" action="{{ route('reports.intervention') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- Client Filter -->
                        <div class="col-md-4">
                            <label for="client_id" class="form-label fw-semibold">{{ __('locale.lbl_client') }}</label>
                            <select name="client_id" id="client_id" class="form-select select2">
                                <option value="">{{ __('locale.lbl_all_clients') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->raison_sociale }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Date Filter -->
                        <div class="col-md-3">
                            <label for="start_date" class="form-label fw-semibold">{{ __('locale.lbl_start_date') }}</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>

                        <!-- End Date Filter -->
                        <div class="col-md-3">
                            <label for="end_date" class="form-label fw-semibold">{{ __('locale.lbl_end_date') }}</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>

                        <!-- Apply Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>{{ __('locale.btn_apply_filters') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Visualization Row -->
        <div class="row g-4 mb-4">
            <!-- Left: Pie Chart -->
            <div class="col-lg-8">
                <div class="card h-100 card-premium shadow-sm">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between py-3">
                        <h5 class="mb-0 fs-6 fw-bold text-uppercase ls-1"><i class="bi bi-pie-chart me-2 text-primary"></i>{{ __('locale.status_distribution') }}</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center p-4">
                        <div id="statusPieChart" style="min-height: 350px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar Stats -->
            <div class="col-lg-4">
                <div class="row g-3 h-100">
                    <!-- Total Interventions -->
                    <div class="col-12">
                        <div class="card card-premium shadow-sm border-0 border-start border-primary border-5">
                            <div class="card-body d-flex flex-column justify-content-center py-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar bg-label-primary p-1 me-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-list-task fs-5"></i>
                                    </div>
                                    <span class="text-muted fw-bold text-uppercase ls-1" style="font-size: 0.75rem;">{{ __('locale.report_total_interventions') }}</span>
                                </div>
                                <h1 class="display-5 mb-0 fw-bold text-primary">{{ $stats['total'] }}</h1>
                            </div>
                        </div>
                    </div>

                    <!-- Most Frequent Status -->
                    <div class="col-12">
                        @if($stats['most_frequent_status'])
                            <div class="card card-premium shadow-sm border-0 border-start border-5" style="border-color: {{ $stats['most_frequent_status']['color'] }} !important;">
                                <div class="card-body d-flex flex-column justify-content-center py-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar p-1 me-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: {{ hexToRgba($stats['most_frequent_status']['color'], 0.1) }}; color: {{ $stats['most_frequent_status']['color'] }};">
                                            <i class="bi bi-award fs-5"></i>
                                        </div>
                                        <span class="text-muted fw-bold text-uppercase ls-1" style="font-size: 0.75rem;">{{ __('locale.most_frequent_status') }}</span>
                                    </div>
                                    <h3 class="mb-1 fw-bold" style="color: {{ $stats['most_frequent_status']['color'] }};">{{ $stats['most_frequent_status']['name'] }}</h3>
                                    <p class="text-muted mb-0 small">{{ $stats['most_frequent_status']['count'] }} {{ $stats['most_frequent_status']['count'] > 1 ? __('locale.menu_interventions') : __('locale.menu_intervention') }}</p>
                                </div>
                            </div>
                        @else
                           <div class="card card-premium shadow-sm border-0 border-start border-secondary border-5">
                                <div class="card-body d-flex flex-column justify-content-center text-center py-4">
                                    <p class="text-muted mb-0">{{ __('locale.no_data') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Most Common Type -->
                    <div class="col-12">
                        <div class="card card-premium shadow-sm border-0 border-start border-info border-5">
                            <div class="card-body d-flex flex-column justify-content-center py-4">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar bg-label-info p-1 me-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-tag fs-5"></i>
                                    </div>
                                    <span class="text-muted fw-bold text-uppercase ls-1" style="font-size: 0.75rem;">{{ __('locale.report_most_common_type') }}</span>
                                </div>
                                <h4 class="mb-0 fw-bold text-truncate" title="{{ $stats['most_common_type'] }}">{{ $stats['most_common_type'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table Card -->
        <div class="card card-premium shadow-sm">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('locale.results') }} ({{ $interventions->total() }})</h5>
                @if($interventions->total() > 0)
                    <div class="card-actions">
                        <!-- Future Export Buttons can go here -->
                    </div>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle table-compact">
                    <thead class="table-light">
                        <tr>
                            <th width="80">{{ __('locale.lbl_id') }}</th>
                            <th class="col-subject">{{ __('locale.lbl_subject') }}</th>
                            <th class="col-client">{{ __('locale.lbl_client') }}</th>
                            <th>{{ __('locale.lbl_type') }}</th>
                            <th>{{ __('locale.lbl_status') }}</th>
                            <th>{{ __('locale.lbl_intervention_date') }}</th>
                            <th class="text-center">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($interventions as $intervention)
                            <tr>
                                <td>
                                    <span class="fw-bold">#{{ $intervention->id }}</span>
                                </td>
                                <td class="col-subject">
                                    <div class="d-flex flex-column">
                                        <span class="text-heading fw-semibold mb-1">{{ $intervention->subject }}</span>
                                        @if($intervention->priority)
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-dot bg-{{ strtolower($intervention->priority) == 'urgent' ? 'danger' : (strtolower($intervention->priority) == 'important' ? 'warning' : 'primary') }} me-1"></span>
                                                <small class="text-muted">{{ $intervention->translated_priority }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-client">
                                    @if($intervention->client)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-building me-2 text-secondary opacity-75"></i>
                                            <span class="text-body">{{ $intervention->client->raison_sociale }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small">{{ __('locale.lbl_no_client') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($intervention->type)
                                        <span class="badge bg-label-info py-1 px-2">{{ $intervention->type_name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($intervention->status)
                                        <span class="badge py-1 px-2" style="background-color: {{ hexToRgba($intervention->status->color ?? '#eee', 0.1) }}; color: {{ $intervention->status->color ?? '#333' }}; font-size: 0.75rem;">
                                            {{ $intervention->status_name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center small">
                                        <i class="bi bi-calendar-event me-1 text-muted"></i>
                                        {{ $intervention->intervention_date ? $intervention->intervention_date->format('d/m/Y') : '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('tech_intervs.show', $intervention->id) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="{{ __('locale.btn_show') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('tech_intervs.pdf', $intervention->id) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="{{ __('locale.download_pdf') }}">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-center text-muted">
                                        <i class="bi bi-inbox fs-1 mb-3"></i>
                                        <p>{{ __('locale.no_interventions_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-top pt-4">
                {{ $interventions->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .card-premium {
        border: none;
        border-radius: 0.75rem;
        background: #fff;
    }
    .table-compact {
        font-size: 0.875rem;
    }
    .table-compact th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table-compact td {
        padding-top: 0.875rem;
        padding-bottom: 0.875rem;
    }
    .col-subject {
        min-width: 200px;
        max-width: 300px;
        white-space: normal !important;
    }
    .col-client {
        min-width: 150px;
        max-width: 250px;
        white-space: normal !important;
    }
    .badge-dot {
        width: 0.4rem;
        height: 0.4rem;
        display: inline-block;
        border-radius: 50%;
    }
    .bg-danger { background-color: #ff3e1d !important; }
    .bg-warning { background-color: #ffab00 !important; }
    .bg-primary { background-color: #91378e !important; }
    .bg-label-primary { background-color: #f3eaf2 !important; color: #91378e !important; }
    .bg-label-success { background-color: #e8fadf !important; color: #71dd37 !important; }
    .bg-label-warning { background-color: #fff2d6 !important; color: #ffab00 !important; }
    .bg-label-secondary { background-color: #ebeef0; color: #8592a3; }
    .bg-label-info { background-color: #e1f0ff; color: #03c3ec; }
    .btn-icon {
        padding: 0;
        width: 2.2rem;
        height: 2.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
</style>
@endsection

@section('scripts')
<script src="{{ asset('libs/apex-charts/apexcharts.js') }}"></script>
<script>
    $(document).ready(function() {
        // Data from PHP
        const statusData = @json($statusCounts);
        const labels = statusData.map(s => s.name);
        const series = statusData.map(s => s.count);
        const colors = statusData.map(s => s.color);

        const options = {
            series: series,
            labels: labels,
            chart: {
                type: 'donut',
                height: 380,
                fontFamily: 'Open Sans, sans-serif'
            },
            colors: colors,
            stroke: {
                show: false
            },
            legend: {
                position: 'bottom',
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                },
                fontSize: '13px',
                markers: {
                    radius: 12,
                    offsetX: -3
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex];
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: ['#fff']
                },
                dropShadow: {
                    enabled: false
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " {{ __('locale.menu_interventions') }}";
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#8e94a9',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 700,
                                color: '#435971',
                                offsetY: 10,
                                formatter: function (val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: "{{ __('locale.lbl_total') }}",
                                color: '#8e94a9',
                                formatter: function (w) {
                                    return {{ $stats['total'] }};
                                }
                            }
                        }
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#statusPieChart"), options);
        chart.render();

        $('.select2').select2({
            width: '100%'
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
