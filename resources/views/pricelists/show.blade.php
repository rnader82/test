@extends('layouts.app')

@section('title', $pricelist->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pricelists.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.pricelists_list_title') }} / {{ $pricelist->name }}</h1>
    </div>
@endsection

@section('content')
<style>
    .btn-odoo-primary {
        background-color: #714B67;
        color: white;
        border: 1px solid #714B67;
        font-weight: 500;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-odoo-primary:hover {
        background-color: #5c3c54;
        border-color: #5c3c54;
        color: white;
    }
    .btn-odoo-secondary {
        background-color: white;
        color: #495057;
        border: 1px solid #ced4da;
        font-weight: 500;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-odoo-secondary:hover {
        background-color: #f8f9fa;
        color: #212529;
    }
    .odoo-sheet {
        background: #ffffff;
        border: 1px solid #dee2e6;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 2.5rem;
        margin-top: 1rem;
        margin-bottom: 2rem;
        border-radius: 4px;
    }
    .odoo-chatter {
        border-left: 1px solid #dee2e6;
        padding-left: 1.5rem;
        min-height: 500px;
    }
    .tab-odoo .nav-link {
        color: #495057;
        font-weight: 500;
        border: none;
        border-bottom: 2px solid transparent;
        padding: 0.5rem 1rem;
    }
    .tab-odoo .nav-link.active {
        color: #714B67;
        border-bottom: 2px solid #714B67;
        background-color: transparent;
        font-weight: bold;
    }
    .odoo-table-gray th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    .chatter-feed {
        margin-top: 1.5rem;
    }
    .chatter-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #714B67;
    }
    .add-line-link {
        color: #714B67;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .add-line-link:hover {
        text-decoration: underline;
    }
</style>

<div class="container-fluid px-4 mt-3">
    <!-- Action buttons at the top -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pricelists.edit', $pricelist->id) }}" class="btn btn-odoo-primary px-3">{{ __('locale.btn_edit') }}</a>
            <a href="{{ route('pricelists.create') }}" class="btn btn-odoo-secondary px-3">{{ __('locale.btn_create') }}</a>
            <button class="btn btn-odoo-secondary px-3" onclick="window.print();">{{ __('locale.btn_print_preview') }}</button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Form Column (Sheet) -->
        <div class="col-lg-8">
            <div class="odoo-sheet">
                <div class="mb-4">
                    <h1 class="fw-bold mb-3" style="color: #212529;">{{ $pricelist->name }}</h1>
                    @if($pricelist->active)
                        <span class="badge bg-success">{{ __('locale.active') }}</span>
                    @else
                        <span class="badge bg-danger">{{ __('locale.inactive') }}</span>
                    @endif
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-bold text-muted" style="width: 120px;">{{ __('locale.lbl_currency') }}</span>
                            <span>{{ $pricelist->currency }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-bold text-muted" style="width: 120px;">{{ __('locale.menu_company') }}</span>
                            <span>{{ $pricelist->company?->name ?? __('locale.lbl_all_companies') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold text-muted" style="width: 120px;">{{ __('locale.lbl_client') }}</span>
                            <span class="fw-semibold text-primary">{{ $pricelist->client?->raison_sociale ?? __('locale.generic_all_clients') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold text-muted" style="width: 150px;">{{ __('locale.lbl_country_groups') }}</span>
                            <span class="text-muted">—</span>
                        </div>
                    </div>
                </div>

                <!-- Tabs for rules -->
                <ul class="nav nav-tabs tab-odoo mb-4" id="pricelistTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-rules" type="button" role="tab" aria-controls="sales-rules" aria-selected="true">
                            {{ __('locale.tab_sales_price') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="recurring-tab" data-bs-toggle="tab" data-bs-target="#recurring-rules" type="button" role="tab" aria-controls="recurring-rules" aria-selected="false">
                            {{ __('locale.tab_recurring_price') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pricelistTabsContent">
                    <!-- Sales Rules Tab -->
                    <div class="tab-pane fade show active" id="sales-rules" role="tabpanel" aria-labelledby="sales-tab">
                        <div class="table-responsive">
                            <table class="table table-hover odoo-table-gray align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('locale.lbl_apply_on') }}</th>
                                        <th>{{ __('locale.lbl_price') }}</th>
                                        <th>{{ __('locale.lbl_unit_price') ?? 'Prix unitaire' }}</th>
                                        <th>{{ __('locale.lbl_discounted_price') ?? 'Prix remisé' }}</th>
                                        <th>{{ __('locale.lbl_min_qty') }}</th>
                                        <th>{{ __('locale.lbl_start_date') }}</th>
                                        <th>{{ __('locale.lbl_end_date') }}</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salesItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->applied_on === '1_product' || $item->applied_on === '0_product_variant')
                                                    {{ $item->equipment?->name ?? __('locale.lbl_unknown_product') }}
                                                @elseif($item->applied_on === '2_product_category')
                                                    {{ __('locale.lbl_category_prefix') }}{{ $item->category?->name ?? __('locale.lbl_unknown_category') }}
                                                @else
                                                    {{ __('locale.lbl_all_products') }}
                                                @endif
                                            </td>
                                            <td class="fw-semibold">
                                                @if($item->compute_price === 'fixed')
                                                    {{ number_format($item->fixed_price, 2, ',', ' ') }} €
                                                @elseif($item->compute_price === 'percentage')
                                                    {{ __('locale.lbl_percent_discount', ['percent' => number_format($item->percent_price, 2, ',', ' ')]) }}
                                                @elseif($item->compute_price === 'formula')
                                                    @php
                                                        $baseName = __('locale.lbl_public_sales_price');
                                                        if($item->base === 'standard_price') $baseName = __('locale.lbl_purchase_cost');
                                                        elseif($item->base === 'pricelist') $baseName = $item->basePricelist?->name ?? __('locale.lbl_other_pricelist');
                                                    @endphp
                                                    @if($item->price_discount != 0)
                                                        @if($item->base === 'standard_price')
                                                            {{ __('locale.lbl_markup_on', ['percent' => number_format($item->price_discount, 2, ',', ' '), 'base' => $baseName]) }}
                                                        @else
                                                            {{ __('locale.lbl_percent_discount_on', ['percent' => number_format($item->price_discount, 2, ',', ' '), 'base' => $baseName]) }}
                                                        @endif
                                                    @else
                                                        {{ __('locale.lbl_based_on', ['base' => $baseName]) }}
                                                    @endif
                                                    @if($item->price_surcharge != 0)
                                                        + {{ number_format($item->price_surcharge, 2, ',', ' ') }} €
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $unitPrice = null;
                                                    $discountedPrice = null;
                                                    if ($item->equipment) {
                                                        $unitPrice = (float) ($item->equipment->price_excl_tax ?? $item->equipment->price_per_unit ?? null);
                                                        if ($unitPrice !== null) {
                                                            if ($item->compute_price === 'fixed') {
                                                                $discountedPrice = (float) $item->fixed_price;
                                                            } elseif ($item->compute_price === 'percentage') {
                                                                $discountedPrice = $unitPrice * (1.0 - ((float) $item->percent_price / 100.0));
                                                            } elseif ($item->compute_price === 'formula') {
                                                                $basePrice = $unitPrice;
                                                                if ($item->base === 'standard_price') {
                                                                    $basePrice = (float) ($item->equipment->purchase_price ?? 0.0);
                                                                }
                                                                $discountedPrice = $basePrice * (1.0 - ((float) $item->price_discount / 100.0));
                                                                $discountedPrice += (float) $item->price_surcharge;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if($unitPrice !== null)
                                                    {{ number_format($unitPrice, 2, ',', ' ') }} {{ $pricelist->currency }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($discountedPrice !== null)
                                                    <span class="fw-semibold text-success">{{ number_format($discountedPrice, 2, ',', ' ') }} {{ $pricelist->currency }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->min_quantity, 2, ',', ' ') }}</td>
                                            <td>{{ $item->date_start ? $item->date_start->format('d/m/Y') : '—' }}</td>
                                            <td>{{ $item->date_end ? $item->date_end->format('d/m/Y') : '—' }}</td>
                                            <td>
                                                <form action="{{ route('pricelists.rules.destroy', [$pricelist->id, $item->id]) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete_rule') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-muted p-0"><i class="bi bi-trash fs-5"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">{{ __('locale.no_rules_defined') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="#" class="add-line-link" data-bs-toggle="modal" data-bs-target="#ruleModal" data-plan="">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_line') }}
                            </a>
                        </div>
                    </div>

                    <!-- Recurring Rules Tab -->
                    <div class="tab-pane fade" id="recurring-rules" role="tabpanel" aria-labelledby="recurring-tab">
                        <div class="table-responsive">
                            <table class="table table-hover odoo-table-gray align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('locale.lbl_apply_on') }}</th>
                                        <th>{{ __('locale.lbl_recurring_plan') }}</th>
                                        <th>{{ __('locale.lbl_price') }}</th>
                                        <th>{{ __('locale.lbl_unit_price') ?? 'Prix unitaire' }}</th>
                                        <th>{{ __('locale.lbl_discounted_price') ?? 'Prix remisé' }}</th>
                                        <th>{{ __('locale.lbl_min_qty') }}</th>
                                        <th>{{ __('locale.lbl_start_date') }}</th>
                                        <th>{{ __('locale.lbl_end_date') }}</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recurringItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->applied_on === '1_product' || $item->applied_on === '0_product_variant')
                                                    {{ $item->equipment?->name ?? __('locale.lbl_unknown_product') }}
                                                @elseif($item->applied_on === '2_product_category')
                                                    {{ __('locale.lbl_category_prefix') }}{{ $item->category?->name ?? __('locale.lbl_unknown_category') }}
                                                @else
                                                    {{ __('locale.lbl_all_products') }}
                                                @endif
                                            </td>
                                            <td class="fw-semibold text-primary">{{ $item->plan_name }}</td>
                                            <td class="fw-semibold">
                                                @if($item->compute_price === 'fixed')
                                                    {{ number_format($item->fixed_price, 2, ',', ' ') }} €
                                                @elseif($item->compute_price === 'percentage')
                                                    {{ __('locale.lbl_percent_discount', ['percent' => number_format($item->percent_price, 2, ',', ' ')]) }}
                                                @elseif($item->compute_price === 'formula')
                                                    @php
                                                        $baseName = __('locale.lbl_public_sales_price');
                                                        if($item->base === 'standard_price') $baseName = __('locale.lbl_purchase_cost');
                                                        elseif($item->base === 'pricelist') $baseName = $item->basePricelist?->name ?? __('locale.lbl_other_pricelist');
                                                    @endphp
                                                    @if($item->price_discount != 0)
                                                        @if($item->base === 'standard_price')
                                                            {{ __('locale.lbl_markup_on', ['percent' => number_format($item->price_discount, 2, ',', ' '), 'base' => $baseName]) }}
                                                        @else
                                                            {{ __('locale.lbl_percent_discount_on', ['percent' => number_format($item->price_discount, 2, ',', ' '), 'base' => $baseName]) }}
                                                        @endif
                                                    @else
                                                        {{ __('locale.lbl_based_on', ['base' => $baseName]) }}
                                                    @endif
                                                    @if($item->price_surcharge != 0)
                                                        + {{ number_format($item->price_surcharge, 2, ',', ' ') }} €
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $recUnitPrice = null;
                                                    $recDiscountedPrice = null;
                                                    if ($item->equipment) {
                                                        $recUnitPrice = (float) ($item->equipment->price_excl_tax ?? $item->equipment->price_per_unit ?? null);
                                                        if ($recUnitPrice !== null) {
                                                            if ($item->compute_price === 'fixed') {
                                                                $recDiscountedPrice = (float) $item->fixed_price;
                                                            } elseif ($item->compute_price === 'percentage') {
                                                                $recDiscountedPrice = $recUnitPrice * (1.0 - ((float) $item->percent_price / 100.0));
                                                            } elseif ($item->compute_price === 'formula') {
                                                                $recBasePrice = $recUnitPrice;
                                                                if ($item->base === 'standard_price') {
                                                                    $recBasePrice = (float) ($item->equipment->purchase_price ?? 0.0);
                                                                }
                                                                $recDiscountedPrice = $recBasePrice * (1.0 - ((float) $item->price_discount / 100.0));
                                                                $recDiscountedPrice += (float) $item->price_surcharge;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if($recUnitPrice !== null)
                                                    {{ number_format($recUnitPrice, 2, ',', ' ') }} {{ $pricelist->currency }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($recDiscountedPrice !== null)
                                                    <span class="fw-semibold text-success">{{ number_format($recDiscountedPrice, 2, ',', ' ') }} {{ $pricelist->currency }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->min_quantity, 2, ',', ' ') }}</td>
                                            <td>{{ $item->date_start ? $item->date_start->format('d/m/Y') : '—' }}</td>
                                            <td>{{ $item->date_end ? $item->date_end->format('d/m/Y') : '—' }}</td>
                                            <td>
                                                <form action="{{ route('pricelists.rules.destroy', [$pricelist->id, $item->id]) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete_recurring_rule') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-muted p-0"><i class="bi bi-trash fs-5"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">{{ __('locale.no_recurring_rules_defined') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="#" class="add-line-link" data-bs-toggle="modal" data-bs-target="#ruleModal" data-plan="Mensuel">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_line') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Chatter Column -->
        <div class="col-lg-4">
            <div class="odoo-chatter">
                <!-- Chatter Logs -->
                <div class="chatter-feed">
                    @forelse($logs as $log)
                        @php
                            $name = $log->user?->name ?? 'System';
                            $words = explode(' ', trim($name));
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($name, 0, 2));
                            }
                        @endphp
                        <div class="d-flex gap-3 mb-4">
                            <div class="chatter-avatar" title="{{ $name }}">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="color: #495057;">{{ $name }}</span>
                                    <small class="text-muted" title="{{ $log->created_at->format('d/m/Y H:i:s') }}">
                                        {{ $log->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="mt-1" style="font-size: 0.9rem; color: #212529;">
                                    @if($log->action === 'ACCESS')
                                        {{ __('locale.chatter_pricelist_consulted') ?? 'Liste de prix consultée' }}
                                    @elseif($log->action === 'CREATE')
                                        {{ __('locale.chatter_pricelist_created') ?? 'Liste de prix créée' }}
                                    @else
                                        {{ $log->description }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            Aucune activité enregistrée.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('pricelists.partials.rule_modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('ruleModal');
        const modalPlanInput = document.getElementById('modal_plan_name');
        const modalTitle = document.getElementById('ruleModalLabel');

        modalElement.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const planName = button.getAttribute('data-plan');

            modalPlanInput.value = planName;

            if (planName) {
                modalTitle.textContent = "{{ __('locale.modal_create_recurring_rules') }}";
            } else {
                modalTitle.textContent = "{{ __('locale.modal_create_rules') }}";
            }
        });
    });
</script>
@endsection
