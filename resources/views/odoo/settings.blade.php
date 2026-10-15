@extends('layouts.app')
@section('portal-module', 'odoo')
@section('portal-page', 'settings')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', 'Odoo Settings Synchronization')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>Odoo Settings Synchronization</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Odoo Connection Status Card -->
        <div class="module-form-card card card-premium mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="card-title mb-1 fw-bold">Odoo Connection Status</h5>
                        <p class="text-muted mb-0">Manage credentials and verify connection parameters with the Odoo 19 API.</p>
                    </div>
                    <div>
                        @if($connectionStatus === 'Connected')
                            <span class="badge bg-success px-3 py-2 text-white fs-6">
                                <i class="bi bi-cloud-check-fill me-1"></i> Connected
                            </span>
                        @elseif($connectionStatus === 'Authentication Failed')
                            <span class="badge bg-danger px-3 py-2 text-white fs-6">
                                <i class="bi bi-cloud-slash-fill me-1"></i> Auth Failed
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-2 text-white fs-6">
                                <i class="bi bi-cloud-slash-fill me-1"></i> Disconnected
                            </span>
                        @endif
                    </div>
                </div>

                @if($connectionError)
                    <div class="mt-3 p-3 bg-light text-danger rounded border border-danger border-opacity-25">
                        <strong>Error details:</strong> {{ $connectionError }}
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="p-3 border rounded bg-light">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Odoo URL</span>
                            <span class="font-monospace text-dark">{{ config('services.odoo.url') ?: 'Not Set' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="p-3 border rounded bg-light">
                            <span class="text-muted d-block small text-uppercase fw-semibold">Database</span>
                            <span class="font-monospace text-dark">{{ config('services.odoo.db') ?: 'Not Set' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <span class="text-muted d-block small text-uppercase fw-semibold">API Key Status</span>
                            <span class="font-monospace text-dark">{{ config('services.odoo.api_key') ? '🔑 Configured' : '❌ Missing' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sync Cards Grid -->
        <h4 class="fw-bold mb-3 mt-4 text-primary"><i class="bi bi-download me-2"></i>Fetch Data from Odoo</h4>
        <div class="row">
            <!-- Product Categories -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-info">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Product Categories</h5>
                                <i class="bi bi-grid-3x3-gap text-info fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch the complete hierarchical category tree (`product.category`) from Odoo to map local equipment categories.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Record Count:</span>
                                <a href="{{ route('odoo_categories.index') }}" class="badge bg-info text-dark fw-bold fs-6 text-decoration-none" title="Manage Odoo Categories">
                                    {{ $stats['categories_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="categories">
                                <button type="submit" class="btn btn-info w-100 text-dark fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Categories
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fiscal Positions -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-warning">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Fiscal Positions</h5>
                                <i class="bi bi-file-earmark-text text-warning fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch Odoo's configured fiscal positions (`account.fiscal.position`) to associate with your local accounting families and clients.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Record Count:</span>
                                <a href="{{ route('odoo_fiscal_positions.index') }}" class="badge bg-warning text-dark fw-bold fs-6 text-decoration-none" title="Manage Fiscal Positions">
                                    {{ $stats['fiscal_positions_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="fiscal_positions">
                                <button type="submit" class="btn btn-warning w-100 text-dark fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Fiscal Positions
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricelists & Rules -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-success">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Pricelists & Rules</h5>
                                <i class="bi bi-currency-dollar text-success fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch all pricelists (`product.pricelist`) and their items/rules from Odoo to keep customer price lists up to date.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Pricelists:</span>
                                <span class="badge bg-success text-white fw-bold fs-6">{{ $stats['pricelists_count'] }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="pricelists">
                                <button type="submit" class="btn btn-success w-100 text-white fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Pricelists & Rules
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurring Plans -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-info">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Plans Récurrents</h5>
                                <i class="bi bi-calendar-check text-info fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch all recurring subscription plans (`sale.subscription.plan`) from Odoo to configure equipment subscription pricing.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Plans:</span>
                                <span class="badge bg-info text-white fw-bold fs-6">{{ $stats['recurring_plans_count'] ?? 0 }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="recurring_plans">
                                <button type="submit" class="btn btn-info w-100 text-white fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Recurring Plans
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Companies -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-primary">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Odoo Companies</h5>
                                <i class="bi bi-building text-primary fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch Odoo companies (`res.company`) and map/link them with local company profiles using Odoo IDs.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Record Count:</span>
                                <span class="badge bg-primary text-white fw-bold fs-6">{{ $stats['companies_count'] }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="companies">
                                <button type="submit" class="btn btn-primary w-100 text-white fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Companies
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taxes -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-danger">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Odoo Taxes</h5>
                                <i class="bi bi-percent text-danger fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch and view active Odoo taxes (`account.tax`) to obtain correct tax IDs for your local accounting configurations.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Record Count:</span>
                                <span class="badge bg-danger text-white fw-bold fs-6">{{ $stats['taxes_count'] }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="taxes">
                                <button type="submit" class="btn btn-danger w-100 text-white fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-search me-1"></i> Query Taxes List
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipments -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-secondary">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Equipment Odoo IDs</h5>
                                <i class="bi bi-tools text-secondary fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch Odoo product templates (`product.template`) to match Odoo IDs with local equipments by matching their reference (`default_code`).</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <span class="badge bg-secondary text-white fw-bold fs-6">{{ $stats['equipments_synced_count'] }} / {{ $stats['equipments_total_count'] }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="equipments">
                                <button type="submit" class="btn btn-secondary w-100 text-white fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Equipment Odoo IDs
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Odoo IDs -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-purple" style="border-top-color: #6f42c1 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Client Odoo IDs</h5>
                                <i class="bi bi-people text-purple fs-3" style="color: #6f42c1;"></i>
                            </div>
                            <p class="text-muted small">Fetch Odoo partners (`res.partner`) to match Odoo IDs with local clients by matching their reference (`ref`).</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <span class="badge bg-purple text-white fw-bold fs-6" style="background-color: #6f42c1;">{{ $stats['clients_synced_count'] }} / {{ $stats['clients_total_count'] }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="clients">
                                <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #6f42c1;" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Client Odoo IDs
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Terms -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4 border-dark">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Payment Terms</h5>
                                <i class="bi bi-credit-card text-dark fs-3"></i>
                            </div>
                            <p class="text-muted small">Fetch payment terms (`account.payment.term`) from Odoo and map them to the local payment conditions table.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Record Count:</span>
                                <span class="badge bg-dark text-white fw-bold fs-6">{{ $stats['payment_terms_count'] ?? 0 }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="payment_terms">
                                <button type="submit" class="btn btn-dark w-100 text-white fw-semibold" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Payment Terms
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Countries -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #20c997 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Country Odoo IDs</h5>
                                <i class="bi bi-globe fs-3" style="color: #20c997;"></i>
                            </div>
                            <p class="text-muted small">Fetch <code>res.country</code> from Odoo and match Odoo IDs to local countries by ISO code or English name.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <span class="badge fw-bold fs-6 text-white" style="background-color: #20c997;">{{ $stats['countries_synced_count'] }} / {{ $stats['countries_count'] }}</span>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="countries">
                                <button type="submit" class="btn w-100 text-white fw-semibold" style="background-color: #20c997;" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Country Odoo IDs
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Languages -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #6f42c1 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Languages</h5>
                                <i class="bi bi-translate fs-3" style="color: #6f42c1;"></i>
                            </div>
                            <p class="text-muted small">Fetch languages (<code>res.lang</code>) from Odoo and synchronize them with local language records.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <a href="{{ route('languages.index') }}" class="badge text-white fw-bold fs-6 text-decoration-none" style="background-color: #6f42c1;" title="Manage Languages">
                                    {{ $stats['languages_synced_count'] }} / {{ $stats['languages_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="languages">
                                <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #6f42c1;" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-cloud-arrow-down me-1"></i> Fetch Languages from Odoo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warehouses -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #fd7e14 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Warehouses</h5>
                                <i class="bi bi-box-seam fs-3" style="color: #fd7e14;"></i>
                            </div>
                            <p class="text-muted small">Fetch warehouses (<code>stock.warehouse</code>) from Odoo and synchronize them with local warehouse records.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <a href="{{ route('warehouses.index') }}" class="badge text-white fw-bold fs-6 text-decoration-none" style="background-color: #fd7e14;" title="Manage Warehouses">
                                    {{ $stats['warehouses_synced_count'] }} / {{ $stats['warehouses_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="warehouses">
                                <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #fd7e14;" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Warehouses
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory & Stock Levels (stock.quant) -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #198754 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Inventory & Stock Levels</h5>
                                <i class="bi bi-boxes fs-3 text-success"></i>
                            </div>
                            <p class="text-muted small">Fetch live stock quants (<code>stock.quant</code>) from Odoo for all internal warehouse locations, updating on-hand and reserved quantities.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Stock Lines (Warehouse):</span>
                                <a href="{{ route('equipments.inventory.index') }}" class="badge bg-success text-white fw-bold fs-6 text-decoration-none" title="Manage Inventory">
                                    {{ $stats['inventory_quants_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Total Units On Hand:</span>
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ number_format($stats['total_stock_units'], 0) }} units
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Last Checkpoint:</span>
                                <span class="badge bg-light text-dark border font-monospace small">
                                    {{ $lastInventorySync ?? 'Never (Full sync)' }}
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('odoo.settings.sync') }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="type" value="inventory">
                                    <button type="submit" class="btn btn-success text-white fw-semibold w-100" {{ !$isConfigured ? 'disabled' : '' }}>
                                        <i class="bi bi-download me-1"></i> Pull Inventory
                                    </button>
                                </form>
                                @if($lastInventorySync)
                                    <form action="{{ route('odoo.settings.sync') }}" method="POST" onsubmit="return confirm('Reset the last inventory synchronization checkpoint?')">
                                        @csrf
                                        <input type="hidden" name="type" value="reset_inventory_sync">
                                        <button type="submit" class="btn btn-outline-secondary" title="Reset Checkpoint">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Statuses (Incremental via write_date) -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #0dcaf0 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Order Statuses</h5>
                                <i class="bi bi-clock-history fs-3" style="color: #0dcaf0;"></i>
                            </div>
                            <p class="text-muted small">Incrementally pull order statuses (<code>state</code>, <code>delivery_status</code>) from Odoo (<code>sale.order</code>) using <code>write_date</code>.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Last Sync Checkpoint:</span>
                                <span class="badge bg-light text-dark border font-monospace small">
                                    {{ $lastOrderStatusSync ?? 'Never (Full sync)' }}
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('odoo.settings.sync') }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="type" value="order_statuses">
                                    <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #0dcaf0;" {{ !$isConfigured ? 'disabled' : '' }}>
                                        <i class="bi bi-arrow-repeat me-1"></i> Fetch Statuses
                                    </button>
                                </form>
                                @if($lastOrderStatusSync)
                                    <form action="{{ route('odoo.settings.sync') }}" method="POST" onsubmit="return confirm('Reset the last synchronization checkpoint?')">
                                        @csrf
                                        <input type="hidden" name="type" value="reset_order_statuses_sync">
                                        <button type="submit" class="btn btn-outline-secondary" title="Reset Checkpoint">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Odoo IDs -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #6610f2 !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Order Odoo IDs</h5>
                                <i class="bi bi-cart-check fs-3" style="color: #6610f2;"></i>
                            </div>
                            <p class="text-muted small">Fetch Odoo sales orders (<code>sale.order</code>) to match Odoo IDs with local orders by matching their internal reference (<code>ref_interne</code>) with Odoo's source document (<code>origin</code>).</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <a href="{{ route('orders.index') }}" class="badge text-white fw-bold fs-6 text-decoration-none" style="background-color: #6610f2;" title="Manage Orders">
                                    {{ $stats['orders_synced_count'] }} / {{ $stats['orders_total_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="orders">
                                <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #6610f2;" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Order Odoo IDs
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Odoo IDs -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #e83e8c !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Delivery Odoo IDs</h5>
                                <i class="bi bi-truck fs-3" style="color: #e83e8c;"></i>
                            </div>
                            <p class="text-muted small">Fetch Odoo delivery orders (<code>stock.picking</code>) to match Odoo IDs with local delivery records by matching their internal reference (<code>ref_interne</code>) with Odoo's source document (<code>origin</code>).</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <a href="{{ route('deliveries.index') }}" class="badge text-white fw-bold fs-6 text-decoration-none" style="background-color: #e83e8c;" title="Manage Deliveries">
                                    {{ $stats['deliveries_synced_count'] }} / {{ $stats['deliveries_total_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <form action="{{ route('odoo.settings.sync') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="deliveries">
                                <button type="submit" class="btn text-white fw-semibold w-100" style="background-color: #e83e8c;" {{ !$isConfigured ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-repeat me-1"></i> Fetch Delivery Odoo IDs
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscriptions & Contracts -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="module-form-card card card-premium h-100 border-top border-4" style="border-top-color: #0d6efd !important;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Subscriptions & Contracts</h5>
                                <i class="bi bi-file-earmark-check fs-3 text-primary"></i>
                            </div>
                            <p class="text-muted small">Fetch subscriptions (<code>sale.order</code>) from Odoo and automatically assign them to clients according to their company ID.</p>
                        </div>
                        <div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Local Sync Progress:</span>
                                <a href="{{ route('contracts.index') }}" class="badge bg-primary text-white fw-bold fs-6 text-decoration-none" title="Manage Contracts">
                                    {{ $stats['contracts_synced_count'] }} / {{ $stats['contracts_total_count'] }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Last Sync Checkpoint:</span>
                                <span class="badge bg-light text-dark border font-monospace small">
                                    {{ $lastSubscriptionSync ?? 'Never (Full sync)' }}
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('odoo.settings.sync') }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="type" value="subscriptions">
                                    <button type="submit" class="btn btn-primary text-white fw-semibold w-100" {{ !$isConfigured ? 'disabled' : '' }}>
                                        <i class="bi bi-arrow-repeat me-1"></i> Fetch Subscriptions
                                    </button>
                                </form>
                                @if($lastSubscriptionSync)
                                    <form action="{{ route('odoo.settings.sync') }}" method="POST" onsubmit="return confirm('Reset the last subscription synchronization checkpoint?')">
                                        @csrf
                                        <input type="hidden" name="type" value="reset_subscriptions_sync">
                                        <button type="submit" class="btn btn-outline-secondary" title="Reset Checkpoint">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taxes Table Display (if retrieved) -->
        @if(count($taxes) > 0)
            <div class="module-form-card card card-premium mt-4 mb-5 border-start border-4 border-danger">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-percent me-2 text-danger"></i>Active Odoo Taxes List</h5>
                    <form action="{{ route('odoo.settings.sync') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="clear_taxes">
                        <button type="submit" class="btn btn-xs btn-outline-secondary">
                            <i class="bi bi-trash me-1"></i> Clear View
                        </button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Odoo ID</th>
                                <th>Tax Name</th>
                                <th>Amount</th>
                                <th>Type of Use</th>
                                <th>Company</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxes as $tax)
                                <tr>
                                    <td class="font-monospace fw-bold">{{ $tax->odoo_id }}</td>
                                    <td>{{ $tax->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ (float) $tax->amount }}%</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $tax->type_tax_use ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if(!empty($tax->company_name))
                                            <span class="small text-muted">{{ $tax->company_name }}</span>
                                        @else
                                            <span class="small text-muted">All Companies</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
