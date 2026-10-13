<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style" dir="ltr" data-theme="theme-bordered"
    data-assets-path="{{ url('/') }}/" data-template="vertical-menu-template-bordered" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('title')
        <title>@yield('title') | Novalyo</title>
    @else
        @php
            $navbarTitle = View::yieldContent('navbar-title');
            $extractedTitle = '';
            if ($navbarTitle) {
                if (preg_match('/<h1>(.*?)<\/h1>/s', $navbarTitle, $matches)) {
                    $extractedTitle = trim(strip_tags($matches[1]));
                } else {
                    $extractedTitle = trim(strip_tags($navbarTitle));
                }
                $extractedTitle = preg_replace('/\s+/', ' ', $extractedTitle);
            }
        @endphp
        <title>{{ $extractedTitle ?: 'Novalyo 360' }} | Novalyo</title>
    @endif
    <meta name="description"
        content="Portail professionnel Novalyo" />
    <meta name="keywords"
        content="dashboard, material, material design, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
    <!-- Canonical SEO -->
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_small.png') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha384-nRgPTkuX86pH8yjPJUAFuASXQSSl2/bBUiNV47vSYpKFxHJhbcrGnmlYpYJMeD7a" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"
        integrity="sha384-He3RckdFB2wffiHOcESa3sf4Ida+ni/fw9SSzAcfY2EPnU1zkK/sLUzw2C5Tyuhj" crossorigin="anonymous">
    <!-- Remixicon CSS -->
    <link href="{{ asset('fonts/remixicon/remixicon.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/flag-icons.css') }}" />
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('libs/node-waves/node-waves.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('css/theme-bordered.css') }}" class="template-customizer-theme-css" />
    <!-- jQuery (necessary for Select2) -->
    <script src="{{ asset('libs/jquery/jquery.js') }}"></script>
    <!-- Select2 for searchable dropdowns -->
    <link href="{{ asset('libs/select2/select2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        integrity="sha384-IrMr0LFnIMa9H6HhC5VVqVuWNEIwspnRLKQc0SUyPj4Cy4s02DiWDZEoJOo5WNK6" crossorigin="anonymous" />
    <!-- Select2 JS -->
    <script src="{{ asset('libs/select2/select2.js') }}"></script>
    <!-- CKEditor for WYSIWYG editor
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"
        integrity="sha384-O17uTisZJc13V+J3ifrBCnoCTgCYymiDZ21mywiTld1Cf4+OVoLvPv70n3yy1eAL" crossorigin="anonymous"></script> -->
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('libs/swiper/swiper.css') }}" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('css/cards-statistics.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/cards-analytics.css') }}" />
    <!-- Quill texteditor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet"
        integrity="sha384-ecIckRi4QlKYya/FQUbBUjS4qp65jF/J87Guw5uzTbO1C1Jfa/6kYmd6dXUF6D7i" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"
        integrity="sha384-reiVBUvj2HgLgCZalqLFSMBVD9LdtiwGyybRGCq2AfP8Rgebz5DxEnwKp4FpagKV" crossorigin="anonymous"></script>
    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"
        integrity="sha384-GPAzSuZc0kFvdIev6wm9zg8gnafE8tLso7rsAYQfc9hAdWCpOcpcNI5W9lWkYcsd" crossorigin="anonymous">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ asset('libs/flatpickr/flatpickr.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('js/config.js') }}?v=novalyo-html-v02"></script>
    @vite(['resources/js/app.js'])
    @stack('styles')

    <!-- Loaded last so copied views/assets also work with the existing Vite build. -->
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}?v=novalyo-html-v02" />
</head>

<body class="novalyo-portal" data-novalyo-density="comfortable" data-portal-user="{{ auth()->id() ?? 'guest' }}">
    <input type="hidden" name="csrfmiddlewaretoken" value="{{ csrf_token() }}">
    <div class="layout-wrapper">
        <div class="layout-container app">
            <aside id="layout-menu" class="sidebar" aria-label="{{ __('locale.portal.navigation') }}">
                <div class="brand">
                    <a href="{{ route('dashboard') }}" class="novalyo-logo-full" aria-label="Novalyo"><span class="novalyo-logo-cross"><i class="fa-solid fa-square-plus" aria-hidden="true"></i></span><span>novalyo</span><span class="novalyo-logo-dot">.</span></a>
                    <button id="desktop-menu-toggle" class="novalyo-symbol portal-menu-toggle" type="button" aria-controls="layout-menu" aria-label="{{ __('locale.portal.toggle_menu') }}"><i class="fa-solid fa-square-plus" aria-hidden="true"></i></button>
                    <button id="mobile-menu-close" class="sidebar-toggle portal-menu-toggle" type="button" aria-controls="layout-menu" aria-expanded="true" aria-label="{{ __('locale.portal.toggle_menu') }}"><i class="fa-regular fa-circle-left" aria-hidden="true"></i></button>
                </div>
                <nav class="nav" id="mainNavigation" aria-label="{{ __('locale.portal.navigation') }}">
                    <div class="nav-quick" id="portal-saved-pages" hidden></div>
                    <ul class="menu-inner"> @auth
                            @if (Auth::user()->hasPermission('dashboard-access') || Auth::user()->is_admin == 1)
                                <li class="menu-item {{ Route::is('dashboard') ? 'active open' : '' }}">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-house-door" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_welcome') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                            <a data-portal-route="dashboard" href="{{ route('dashboard') }}" class="menu-link">
                                                <i class="bi bi-speedometer2" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_dashboard') }}</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            <li class="menu-item {{ Route::is('reports.*') ? 'active' : '' }}">
                                @if (Auth::user()->hasPermission('reports-access') || Auth::user()->is_admin == 1)
                                    <a data-portal-route="reports.index" href="{{ route('reports.index') }}" class="menu-link">
                                        <i class="bi bi-bar-chart" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_reports') }}</div>
                                    </a>
                                @endif
                            </li>
                            <li class="menu-item {{ Route::is('todos.*') ? 'active open' : '' }}">
                                @if (Auth::user()->hasPermission('todo-access') || Auth::user()->is_admin == 1)
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-list-task" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_todo') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @if (Auth::user()->hasPermission('remaining-tasks-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item">
                                            <a data-portal-route="todos.index" href="{{ route('todos.index') }}" class="menu-link">
                                                <i class="bi bi-list-check" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_remaining_tasks') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('assigned-to-me-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item">
                                            <a data-portal-route="todos.index" href="{{ route('todos.index', ['filters' => 'assigned_to_me']) }}"
                                                class="menu-link">
                                                <i class="bi bi-person-check" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_assigned_to_me') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('done-tasks-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item">
                                            <a data-portal-route="todos.index" href="{{ route('todos.index', ['filters' => 'done']) }}"
                                                class="menu-link">
                                                <i class="bi bi-check-square" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_done') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                @endif
                            </li>
                                <li class="portal-section-label">{{ __('locale.portal.relations') }}</li>
                            <li class="menu-item {{ Route::is('clients.*') || Route::is('prospects.*') || Route::is('contracts.*') || Route::is('contacts.*') ? 'active open' : '' }}">
                                @if (Auth::user()->hasPermission('clients-access') || Auth::user()->is_admin == 1)
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-building" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_clients') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @if (Auth::user()->hasPermission('view-contacts') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('contacts.*') ? 'active' : '' }}">
                                            <a data-portal-route="contacts.index" href="{{ route('contacts.index') }}" class="menu-link">
                                                <i class="bi bi-person-lines-fill" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_contacts') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('clients-list-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('clients.*') ? 'active' : '' }}">
                                            <a data-portal-route="clients.index" href="{{ route('clients.index') }}" class="menu-link">
                                                <i class="bi bi-tags" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_companies') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('view-contracts') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('contracts.*') ? 'active' : '' }}">
                                            <a data-portal-route="contracts.index" href="{{ route('contracts.index') }}" class="menu-link">
                                                <i class="bi bi-journal-text" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_contracts') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                @endif
                                </li>
                                <li class="menu-item {{ Route::is('equipments.*') || Route::is('warehouses.*') || Route::is('equipment_categories.*') || Route::is('combos.*') ? 'active open' : '' }}">
                                @if (Auth::user()->hasPermission('equipments-access') || Auth::user()->is_admin == 1)
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-tags" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_equipments') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @if (Auth::user()->hasPermission('view-equipments') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('equipments.*') && !Route::is('equipments.inventory.*') ? 'active' : '' }}">
                                            <a data-portal-route="equipments.index" href="{{ route('equipments.index') }}" class="menu-link">
                                                <i class="bi bi-tag" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_equipments') }}</div>
                                            </a>
                                        </li>
                                        @endif

                                        @if (Auth::user()->hasPermission('view-combos') || Auth::user()->hasPermission('combos-access') || Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin())
                                        <li class="menu-item {{ Route::is('combos.*') ? 'active' : '' }}">
                                            <a data-portal-route="combos.index" href="{{ route('combos.index') }}" class="menu-link">
                                                <i class="bi bi-layers" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_combos') ?? 'Choix de Combos' }}</div>
                                            </a>
                                        </li>
                                        @endif

                                        @if (Auth::user()->hasPermission('inventory-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('equipments.inventory.*') || Route::is('warehouses.*') ? 'active open' : '' }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-box-seam" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_inventory') }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                @if (Auth::user()->hasPermission('inventory-dashboard-access') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('equipments.inventory.index') ? 'active' : '' }}">
                                                    <a data-portal-route="equipments.inventory.index" href="{{ route('equipments.inventory.index') }}" class="menu-link">
                                                        <i class="bi bi-speedometer2" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_inventory_dashboard') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-warehouses') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('warehouses.*') ? 'active' : '' }}">
                                                    <a data-portal-route="warehouses.index" href="{{ route('warehouses.index') }}" class="menu-link">
                                                        <i class="bi bi-shop" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_warehouses') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('inventory-history-access') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('equipments.inventory.history') ? 'active' : '' }}">
                                                    <a data-portal-route="equipments.inventory.history" href="{{ route('equipments.inventory.history') }}" class="menu-link">
                                                        <i class="bi bi-clock-history" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.inventory_history') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </li>
                                        @endif

                                        @if (Auth::user()->hasPermission('view-equipment-categories') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('equipment_categories.*') ? 'active' : '' }}">
                                            <a data-portal-route="equipment_categories.index" href="{{ route('equipment_categories.index') }}" class="menu-link">
                                                <i class="bi bi-folder2" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.equipment_categories') ?? 'Catégories d\'équipements' }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                @endif
                                </li>

                                <li class="menu-item {{ Route::is('employees.*') || Route::is('employee_equipments.*') ? 'active open' : '' }}">
                                @if (Auth::user()->hasPermission('collaborateurs-access') || Auth::user()->hasPermission('employees-access') || Auth::user()->hasPermission('view-employees') || Auth::user()->hasPermission('employee-equipments-access') || Auth::user()->hasPermission('view-employee-equipments') || Auth::user()->is_admin == 1)
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-people" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_collaborateurs_parent') ?? 'Collaborateurs' }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @if (Auth::user()->hasPermission('view-employees') || Auth::user()->hasPermission('employees-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('employees.*') ? 'active' : '' }}">
                                            <a data-portal-route="employees.index" href="{{ route('employees.index') }}" class="menu-link">
                                                <i class="bi bi-person-lines-fill" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_employees') ?? 'Collaborateurs' }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('view-employee-equipments') || Auth::user()->hasPermission('employee-equipments-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('employee_equipments.*') ? 'active' : '' }}">
                                            <a data-portal-route="employee_equipments.index" href="{{ route('employee_equipments.index') }}" class="menu-link">
                                                <i class="bi bi-laptop" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_employee_equipments') ?? 'Matériel Collaborateurs' }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                @endif
                                </li>

                                @if (Auth::user()->hasPermission('purchasing-access') || Auth::user()->is_admin == 1)
                                <li class="portal-section-label">{{ __('locale.portal.operations') }}</li>
                            <li class="menu-item {{ Route::is('suppliers.*') || Route::is('purchases.*') || Route::is('supplier-orders.*') || Route::is('supplier-contacts.*') ? 'active open' : '' }}">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-cart-check" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_purchasing') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @if (Auth::user()->hasPermission('view-suppliers') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('suppliers.*') ? 'active' : '' }}">
                                            <a data-portal-route="suppliers.index" href="{{ route('suppliers.index') }}" class="menu-link">
                                                <i class="bi bi-truck" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_suppliers') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('view-supplier-contacts') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('supplier-contacts.*') ? 'active' : '' }}">
                                            <a data-portal-route="supplier-contacts.index" href="{{ route('supplier-contacts.index') }}" class="menu-link">
                                                <i class="bi bi-person-lines-fill" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_supplier_contacts') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('view-purchases') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('purchases.*') ? 'active' : '' }}">
                                            <a data-portal-route="purchases.index" href="{{ route('purchases.index') }}" class="menu-link">
                                                <i class="bi bi-receipt" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_purchases') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('view-supplier-orders') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('supplier-orders.*') ? 'active' : '' }}">
                                            <a data-portal-route="supplier-orders.index" href="{{ route('supplier-orders.index') }}" class="menu-link">
                                                <i class="bi bi-cart-check" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_supplier_orders') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                <li class="menu-item {{ Route::is('issues.devis') || Route::is('pricelists.*') ? 'active open' : '' }}">
                                    @if (Auth::user()->hasPermission('commerce-access') || Auth::user()->is_admin == 1)
                                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                                            <i class="bi bi-bag" style="margin-right: 10px;"></i>
                                            <div>{{ __('locale.menu_commerce') }}</div>
                                        </a>
                                        <ul class="menu-sub">
                                            @if (Auth::user()->hasPermission('commerce-business-access') || Auth::user()->is_admin == 1)
                                            <li class="menu-item">
                                                <a data-portal-route="commerce.business" href="{{-- route('commerce.business') --}}" class="menu-link">
                                                    <i class="bi bi-briefcase" style="margin-right: 10px;"></i>
                                                    <div>{{ __('locale.menu_business') }}</div>
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->hasPermission('commerce-estimate-ticket-access') || Auth::user()->is_admin == 1)
                                            <li class="menu-item {{ Route::is('issues.devis') ? 'active' : '' }}">
                                                <a data-portal-route="issues.devis" href="{{ route('issues.devis') }}" class="menu-link">
                                                    <i class="bi bi-calculator" style="margin-right: 10px;"></i>
                                                    <div>{{ __('locale.menu_estimate_ticket') }}</div>
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->hasPermission('view-pricelists') || Auth::user()->is_admin == 1)
                                            <li class="menu-item {{ Route::is('pricelists.*') ? 'active' : '' }}">
                                                <a data-portal-route="pricelists.index" href="{{ route('pricelists.index') }}" class="menu-link">
                                                    <i class="bi bi-percent" style="margin-right: 10px;"></i>
                                                    <div>{{ __('locale.menu_pricelists') }}</div>
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->hasPermission('commerce-report-access') || Auth::user()->is_admin == 1)
                                            <li class="menu-item">
                                                <a data-portal-route="commerce.report" href="{{-- route('commerce.report') --}}" class="menu-link">
                                                    <i class="bi bi-file-earmark-bar-graph" style="margin-right: 10px;"></i>
                                                    <div>{{ __('locale.menu_report') }}</div>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    @endif
                                </li>
                                @if (Auth::user()->hasPermission('view-orders') || Auth::user()->hasPermission('order-access') || Auth::user()->is_admin == 1)
                                <li class="menu-item {{ Route::is('orders.*') || Route::is('quote-templates.*') || Route::is('quote-reserves.*') ? 'active open' : '' }}">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-box" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_order') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        <li class="menu-item {{ Route::is('orders.*') ? 'active' : '' }}">
                                            <a data-portal-route="orders.index" href="{{ route('orders.index') }}" class="menu-link">
                                                <i class="bi bi-cart3" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_orders') ?? 'Commandes' }}</div>
                                            </a>
                                        </li>
                                        @if (Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('quote-templates.*') ? 'active' : '' }}">
                                            <a data-portal-route="quote-templates.index" href="{{ route('quote-templates.index') }}" class="menu-link">
                                                <i class="bi bi-file-earmark-spreadsheet" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_quote_templates') ?? 'Modèles de devis' }}</div>
                                            </a>
                                        </li>
                                        <li class="menu-item {{ Route::is('quote-reserves.*') ? 'active' : '' }}">
                                            <a data-portal-route="quote-reserves.index" href="{{ route('quote-reserves.index') }}" class="menu-link">
                                                <i class="bi bi-shield-check" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_quote_reserves') ?? 'Réserves de devis' }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                @if (Auth::user()->hasPermission('view-deliveries') || Auth::user()->hasPermission('deliveries-access') || Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin())
                                <li class="menu-item {{ Route::is('deliveries.*') ? 'active' : '' }}">
                                    <a data-portal-route="deliveries.index" href="{{ route('deliveries.index') }}" class="menu-link">
                                        <i class="bi bi-truck" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_deliveries') ?? 'Livraisons' }}</div>
                                    </a>
                                </li>
                                @endif
                                @if (Auth::user()->hasPermission('project-management-access') || Auth::user()->is_admin == 1)
                                <li class="menu-item {{ Route::is('projects.*') ? 'active' : '' }}">
                                    <a data-portal-route="projects.index" href="{{ route('projects.index') }}" class="menu-link">
                                        <i class="bi bi-diagram-3" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_project_management') }}</div>
                                    </a>
                                </li>
                                @endif
                                @if (Auth::user()->hasPermission('view-construction-reports') || Auth::user()->is_admin == 1)
                                <li class="menu-item {{ Route::is('construction-reports.*') ? 'active' : '' }}">
                                    <a data-portal-route="construction-reports.index" href="{{ route('construction-reports.index') }}" class="menu-link">
                                        <i class="bi bi-file-earmark-medical" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.cr_title') }}</div>
                                    </a>
                                </li>
                                @endif
                                @if (Auth::user()->hasPermission('technical-access') || Auth::user()->is_admin == 1)
                                <li class="menu-item {{ Route::is('visite-prev.*') || Route::is('issues.index') || Route::is('tech_intervs.*') || Route::is('repairs.*') || Route::is('planning.*') || Route::is('knowledge-base.*') ? 'active open' : '' }}">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-tools" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_technical') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @if (Auth::user()->hasPermission('technical-visite-prev-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('visite-prev.*') ? 'active' : '' }}">
                                            <a data-portal-route="visite-prev.index" href="{{ route('visite-prev.index') }}" class="menu-link">
                                                <i class="bi bi-shield-check" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_visite_prev') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('menu-tickets') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('issues.index') ? 'active' : '' }}">
                                            <a data-portal-route="issues.index" href="{{ route('issues.index') }}" class="menu-link">
                                                <i class="bi bi-ticket-perforated" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_tickets') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('menu-interventions') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('tech_intervs.*') ? 'active' : '' }}">
                                            <a data-portal-route="tech_intervs.index" href="{{ route('tech_intervs.index') }}" class="menu-link">
                                                <i class="bi bi-cone-striped" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_interventions') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('menu-repair') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('repairs.*') ? 'active' : '' }}">
                                            <a data-portal-route="repairs.index" href="{{ route('repairs.index') }}" class="menu-link">
                                                <i class="bi bi-wrench" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_repairs') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('menu-planning') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('planning.*') ? 'active' : '' }}">
                                            <a data-portal-route="planning.week" href="{{ route('planning.week') }}" class="menu-link">
                                                <i class="bi bi-calendar3" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_planification') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                        @if (Auth::user()->hasPermission('technical-knowledge-base-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('knowledge-base.*') ? 'active' : '' }}">
                                            <a data-portal-route="knowledge-base.index" href="{{ route('knowledge-base.index') }}" class="menu-link">
                                                <i class="bi bi-book" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_knowledge_base') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                            @if (Auth::user()->hasPermission('facturation-access') || Auth::user()->is_admin == 1)
                            <li class="menu-item {{ Route::is('facturation.index') ? 'active' : '' }}">
                                <a data-portal-route="facturation.index" href="{{-- route('facturation.index') --}}" class="menu-link">
                                    <i class="bi bi-file-earmark-spreadsheet"
                                        style="margin-right: 10px;"></i>
                                    <div>{{ __('locale.menu_facturation') }}</div>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->hasPermission('recouvrement-access') || Auth::user()->is_admin == 1)
                            <li class="menu-item {{ Route::is('recouvrement.index') ? 'active' : '' }}">
                                <a data-portal-route="recouvrement.index" href="{{-- route('recouvrement.index') --}}" class="menu-link">
                                    <i class="bi bi-cash-stack" style="margin-right: 10px;"></i>
                                    <div>{{ __('locale.menu_recouvrement') }}</div>
                                </a>
                            </li>
                            @endif

                            <li class="portal-section-label">{{ __('locale.portal.configuration') }}</li>
                            <li class="menu-item {{
                                Route::is('users.*') ||
                                Route::is('roles.*') ||
                                Route::is('permissions.*') ||
                                Route::is('user_assignments.*') ||
                                Route::is('commercials.*') ||
                                Route::is('companies.*') ||
                                Route::is('tenants.*') ||
                                Route::is('countries.*') ||
                                Route::is('languages.*') ||
                                Route::is('client-document-types.*') ||
                                Route::is('contract_templates.*') ||
                                Route::is('contract_clause_templates.*') ||
                                Route::is('equipment_types.*') ||
                                Route::is('brands.*') ||
                                Route::is('issues_groups.*') ||
                                Route::is('categories.*') ||
                                Route::is('issue_types.*') ||
                                Route::is('statuses.*') ||
                                Route::is('states.*') ||
                                Route::is('issue_criticities.*') ||
                                Route::is('issue_severities.*') ||
                                Route::is('origines.*') ||
                                Route::is('tech_intervention_types.*') ||
                                Route::is('tech_intervention_statuses.*') ||
                                Route::is('tech_intervention_states.*') ||
                                Route::is('tech_intervention_severities.*') ||
                                Route::is('process_stages.*') ||
                                Route::is('installers.*') ||
                                Route::is('vp-checklist-templates.*') ||
                                Route::is('odoo.settings') ||
                                Route::is('odoo_categories.*') ||
                                Route::is('odoo_fiscal_positions.*') ||
                                Route::is('accounting_families.*') ||
                                Route::is('remises_ascom.*') ||
                                Route::is('message_templates.*') ||
                                Route::is('logs.*') ? 'active open' : ''
                            }}">
                                @if (Auth::user()->hasPermission('settings-access') || Auth::user()->is_admin == 1)
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="bi bi-gear" style="margin-right: 10px;"></i>
                                        <div>{{ __('locale.menu_settings') }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        {{-- 1. Utilisateurs & Droits (Users & Access) --}}
                                        @if (
                                            Auth::user()->hasPermission('manage-users') ||
                                            Auth::user()->hasPermission('manage-roles') ||
                                            Auth::user()->hasPermission('manage-permissions') ||
                                            Auth::user()->hasPermission('view-user-assignments') ||
                                            Auth::user()->hasPermission('view-commercials') ||
                                            Auth::user()->is_admin == 1
                                        )
                                        <li class="menu-item {{
                                            Route::is('users.*') ||
                                            Route::is('roles.*') ||
                                            Route::is('permissions.*') ||
                                            Route::is('user_assignments.*') ||
                                            Route::is('commercials.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-people" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_users_access') ?? 'Utilisateurs & Droits' }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                @if (Auth::user()->hasPermission('manage-users') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('users.*') ? 'active' : '' }}">
                                                    <a data-portal-route="users.index" href="{{ route('users.index') }}" class="menu-link">
                                                        <i class="bi bi-person" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_users') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('manage-roles') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('roles.*') ? 'active' : '' }}">
                                                    <a data-portal-route="roles.index" href="{{ route('roles.index') }}" class="menu-link">
                                                        <i class="bi bi-shield-lock" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_roles') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('manage-permissions') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('permissions.*') ? 'active' : '' }}">
                                                    <a data-portal-route="permissions.index" href="{{ route('permissions.index') }}" class="menu-link">
                                                        <i class="bi bi-check2-circle" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_permissions') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-user-assignments') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('user_assignments.*') ? 'active' : '' }}">
                                                    <a data-portal-route="user_assignments.index" href="{{ route('user_assignments.index') }}" class="menu-link">
                                                        <i class="bi bi-person-check" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_user_assignments') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-commercials') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('commercials.*') ? 'active' : '' }}">
                                                    <a data-portal-route="commercials.index" href="{{ route('commercials.index') }}" class="menu-link">
                                                        <i class="bi bi-briefcase" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_commercials') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 2. Organisation & Structure (Company & Structure) --}}
                                        @if (
                                            Auth::user()->hasPermission('view-companies') ||
                                            Auth::user()->hasPermission('view-tenants') ||
                                            Auth::user()->hasPermission('settings-access') ||
                                            Auth::user()->is_admin == 1
                                        )
                                        <li class="menu-item {{
                                            Route::is('companies.*') ||
                                            Route::is('tenants.*') ||
                                            Route::is('countries.*') ||
                                            Route::is('languages.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-building" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_organization') ?? 'Organisation' }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                @if (Auth::user()->hasPermission('view-companies') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('companies.*') ? 'active' : '' }}">
                                                    <a data-portal-route="companies.index" href="{{ route('companies.index') }}" class="menu-link">
                                                        <i class="bi bi-building" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_companies') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-tenants') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('tenants.*') ? 'active' : '' }}">
                                                    <a data-portal-route="tenants.index" href="{{ route('tenants.index') }}" class="menu-link">
                                                        <i class="bi bi-stack" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_tenants') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('settings-access') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('countries.*') ? 'active' : '' }}">
                                                    <a data-portal-route="countries.index" href="{{ route('countries.index') }}" class="menu-link">
                                                        <i class="bi bi-globe" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_countries') ?? 'Pays' }}</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('languages.*') ? 'active' : '' }}">
                                                    <a data-portal-route="languages.index" href="{{ route('languages.index') }}" class="menu-link">
                                                        <i class="bi bi-translate" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.lbl_langue') ?? 'Langues' }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 3. Documents & Contrats (Documents & Contracts) --}}
                                        @if (
                                            Auth::user()->hasPermission('view-client-document-types') ||
                                            Auth::user()->hasPermission('admin-manage-contract-templates') ||
                                            Auth::user()->hasPermission('settings-access') ||
                                            Auth::user()->is_admin == 1
                                        )
                                        <li class="menu-item {{
                                            Route::is('client-document-types.*') ||
                                            Route::is('contract_templates.*') ||
                                            Route::is('contract_clause_templates.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-file-earmark-text" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_documents_contracts') ?? 'Documents & Contrats' }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                @if (Auth::user()->hasPermission('view-client-document-types') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('client-document-types.*') ? 'active' : '' }}">
                                                    <a data-portal-route="client-document-types.index" href="{{ route('client-document-types.index') }}" class="menu-link">
                                                        <i class="bi bi-file-earmark-medical" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_client_document_types') ?? 'Types de documents' }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('admin-manage-contract-templates') || Auth::user()->hasPermission('settings-access') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('contract_templates.*') ? 'active' : '' }}">
                                                    <a data-portal-route="contract_templates.index" href="{{ route('contract_templates.index') }}" class="menu-link">
                                                        <i class="bi bi-journal-text" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_contract_templates') ?? 'Modèles de contrat' }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('admin-manage-contract-templates') || Auth::user()->hasPermission('settings-access') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('contract_clause_templates.*') ? 'active' : '' }}">
                                                    <a data-portal-route="contract_clause_templates.index" href="{{ route('contract_clause_templates.index') }}" class="menu-link">
                                                        <i class="bi bi-card-checklist" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_contract_clause_templates') ?? 'Modèles de clauses' }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 4. Articles & Catalogue (Equipment & Catalog Settings) --}}
                                        @if (
                                            Auth::user()->hasPermission('admin-manage-equipment-types') ||
                                            Auth::user()->hasPermission('settings-access') ||
                                            Auth::user()->is_admin == 1
                                        )
                                        <li class="menu-item {{
                                            Route::is('equipment_types.*') ||
                                            Route::is('brands.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-tag" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_catalog_settings') ?? 'Articles & Catalogue' }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                <li class="menu-item {{ Route::is('equipment_types.*') ? 'active' : '' }}">
                                                    <a data-portal-route="equipment_types.index" href="{{ route('equipment_types.index') }}" class="menu-link">
                                                        <i class="bi bi-collection" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.equipment_types') ?? 'Types d\'article' }}</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('brands.*') ? 'active' : '' }}">
                                                    <a data-portal-route="brands.index" href="{{ route('brands.index') }}" class="menu-link">
                                                        <i class="bi bi-award" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.brands') ?? 'Marques' }}</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 5. Tickets / SAV (Issue Settings) --}}
                                        @if (Auth::user()->hasPermission('manage-ticket-settings') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{
                                            Route::is('issues_groups.*') ||
                                            Route::is('categories.*') ||
                                            Route::is('issue_types.*') ||
                                            Route::is('statuses.*') ||
                                            Route::is('states.*') ||
                                            Route::is('issue_criticities.*') ||
                                            Route::is('issue_severities.*') ||
                                            Route::is('warranty_types.*') ||
                                            Route::is('repair_diagnoses.*') ||
                                            Route::is('origines.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-ticket-detailed" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_issue_settings') }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                @if (Auth::user()->hasPermission('view-ticket-groups') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('issues_groups.*') ? 'active' : '' }}">
                                                    <a data-portal-route="issues_groups.index" href="{{ route('issues_groups.index') }}" class="menu-link">
                                                        <i class="bi bi-collection" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_ticket_groups') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('manage-categories') || Auth::user()->is_admin == 1)
                                                    <li class="menu-item {{ Route::is('categories.*') ? 'active' : '' }}">
                                                        <a data-portal-route="categories.index" href="{{ route('categories.index') }}" class="menu-link">
                                                            <i class="bi bi-bookmark" style="margin-right: 10px;"></i>
                                                            <div>{{ __('locale.menu_categories') }}</div>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-ticket-types') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('issue_types.*') ? 'active' : '' }}">
                                                    <a data-portal-route="issue_types.index" href="{{ route('issue_types.index') }}" class="menu-link">
                                                        <i class="bi bi-tags" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_issue_types') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-ticket-statuses') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('statuses.*') ? 'active' : '' }}">
                                                    <a data-portal-route="statuses.index" href="{{ route('statuses.index') }}" class="menu-link">
                                                        <i class="bi bi-info-square" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_statuses') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-ticket-states') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('states.*') ? 'active' : '' }}">
                                                    <a data-portal-route="states.index" href="{{ route('states.index') }}" class="menu-link">
                                                        <i class="bi bi-activity" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_states') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-ticket-criticities') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('issue_criticities.*') ? 'active' : '' }}">
                                                    <a data-portal-route="issue_criticities.index" href="{{ route('issue_criticities.index') }}" class="menu-link">
                                                        <i class="bi bi-lightning" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_issue_criticities') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-ticket-severities') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('issue_severities.*') ? 'active' : '' }}">
                                                    <a data-portal-route="issue_severities.index" href="{{ route('issue_severities.index') }}" class="menu-link">
                                                        <i class="bi bi-exclamation-octagon" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_issue_severities') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('manage-origines') || Auth::user()->is_admin == 1)
                                                    <li class="menu-item {{ Route::is('origines.*') ? 'active' : '' }}">
                                                        <a data-portal-route="origines.index" href="{{ route('origines.index') }}" class="menu-link">
                                                            <i class="bi bi-compass" style="margin-right: 10px;"></i>
                                                            <div>{{ __('locale.menu_origines') }}</div>
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="menu-item {{ Route::is('warranty_types.*') ? 'active' : '' }}">
                                                    <a data-portal-route="warranty_types.index" href="{{ route('warranty_types.index') }}" class="menu-link">
                                                        <i class="bi bi-shield-check" style="margin-right: 10px;"></i>
                                                        <div>Types de garantie SAV</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('repair_diagnoses.*') ? 'active' : '' }}">
                                                    <a data-portal-route="repair_diagnoses.index" href="{{ route('repair_diagnoses.index') }}" class="menu-link">
                                                        <i class="bi bi-clipboard2-check" style="margin-right: 10px;"></i>
                                                        <div>Diagnostics / Résultats SAV</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 6. Interventions Techniques & VP (Tech Interventions Settings) --}}
                                        @if (
                                            Auth::user()->hasPermission('manage-tech-interv-settings') ||
                                            Auth::user()->hasPermission('view-installers') ||
                                            Auth::user()->hasPermission('settings-access') ||
                                            Auth::user()->is_admin == 1
                                        )
                                        <li class="menu-item {{
                                            Route::is('tech_intervention_types.*') ||
                                            Route::is('tech_intervention_statuses.*') ||
                                            Route::is('tech_intervention_states.*') ||
                                            Route::is('tech_intervention_severities.*') ||
                                            Route::is('process_stages.*') ||
                                            Route::is('installers.*') ||
                                            Route::is('vp-checklist-templates.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-tools" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_tech_interventions') }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                @if (Auth::user()->hasPermission('view-tech-types') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('tech_intervention_types.*') ? 'active' : '' }}">
                                                    <a data-portal-route="tech_intervention_types.index" href="{{ route('tech_intervention_types.index') }}" class="menu-link">
                                                        <i class="bi bi-collection" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_types') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-tech-statuses') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('tech_intervention_statuses.*') ? 'active' : '' }}">
                                                    <a data-portal-route="tech_intervention_statuses.index" href="{{ route('tech_intervention_statuses.index') }}" class="menu-link">
                                                        <i class="bi bi-info-circle" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_statuses') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-tech-states') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('tech_intervention_states.*') ? 'active' : '' }}">
                                                    <a data-portal-route="tech_intervention_states.index" href="{{ route('tech_intervention_states.index') }}" class="menu-link">
                                                        <i class="bi bi-activity" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_states') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-tech-severities') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('tech_intervention_severities.*') ? 'active' : '' }}">
                                                    <a data-portal-route="tech_intervention_severities.index" href="{{ route('tech_intervention_severities.index') }}" class="menu-link">
                                                        <i class="bi bi-exclamation-triangle" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_severity') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-progress-stages') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('process_stages.*') ? 'active' : '' }}">
                                                    <a data-portal-route="process_stages.index" href="{{ route('process_stages.index') }}" class="menu-link">
                                                        <i class="bi bi-bar-chart-steps" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_progress_stages') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('view-installers') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('installers.*') ? 'active' : '' }}">
                                                    <a data-portal-route="installers.index" href="{{ route('installers.index') }}" class="menu-link">
                                                        <i class="bi bi-diagram-2" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_installers') }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                                @if (Auth::user()->hasPermission('settings-access') || Auth::user()->is_admin == 1)
                                                <li class="menu-item {{ Route::is('vp-checklist-templates.*') ? 'active' : '' }}">
                                                    <a data-portal-route="vp-checklist-templates.index" href="{{ route('vp-checklist-templates.index') }}" class="menu-link">
                                                        <i class="bi bi-card-checklist" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_vp_checklist_templates') ?? 'Modèles de checklist' }}</div>
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 7. Comptabilité & Odoo (Accounting & Odoo) --}}
                                        @if (Auth::user()->hasPermission('settings-access') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{
                                            Route::is('odoo.settings') ||
                                            Route::is('odoo_categories.*') ||
                                            Route::is('odoo_fiscal_positions.*') ||
                                            Route::is('accounting_families.*') ||
                                            Route::is('remises_ascom.*') ? 'active open' : ''
                                        }}">
                                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                                <i class="bi bi-calculator" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.menu_accounting_odoo') ?? 'Comptabilité & Odoo' }}</div>
                                            </a>
                                            <ul class="menu-sub">
                                                <li class="menu-item {{ Route::is('odoo.settings') ? 'active' : '' }}">
                                                    <a data-portal-route="odoo.settings" href="{{ route('odoo.settings') }}" class="menu-link">
                                                        <i class="bi bi-cloud-arrow-down" style="margin-right: 10px;"></i>
                                                        <div>Odoo Settings</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('odoo_categories.*') ? 'active' : '' }}">
                                                    <a data-portal-route="odoo_categories.index" href="{{ route('odoo_categories.index') }}" class="menu-link">
                                                        <i class="bi bi-folder2" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_odoo_categories') ?? 'Catégories Odoo' }}</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('odoo_fiscal_positions.*') ? 'active' : '' }}">
                                                    <a data-portal-route="odoo_fiscal_positions.index" href="{{ route('odoo_fiscal_positions.index') }}" class="menu-link">
                                                        <i class="bi bi-receipt" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.menu_odoo_fiscal_positions') ?? 'Positions Fiscales' }}</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('accounting_families.*') ? 'active' : '' }}">
                                                    <a data-portal-route="accounting_families.index" href="{{ route('accounting_families.index') }}" class="menu-link">
                                                        <i class="bi bi-calculator" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.lbl_accounting_family') ?? 'Familles Comptables' }}</div>
                                                    </a>
                                                </li>
                                                <li class="menu-item {{ Route::is('remises_ascom.*') ? 'active' : '' }}">
                                                    <a data-portal-route="remises_ascom.index" href="{{ route('remises_ascom.index') }}" class="menu-link">
                                                        <i class="bi bi-percent" style="margin-right: 10px;"></i>
                                                        <div>{{ __('locale.remises_ascom') ?? 'Remises Ascom' }}</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        @endif

                                        {{-- 8. Modèles de messages (Message Templates) --}}
                                        @if (Auth::user()->hasPermission('view-message-templates') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('message_templates.*') ? 'active' : '' }}">
                                            <a data-portal-route="message_templates.index" href="{{ route('message_templates.index') }}" class="menu-link">
                                                <i class="bi bi-envelope" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.message_templates') }}</div>
                                            </a>
                                        </li>
                                        @endif

                                        {{-- 9. Logs --}}
                                        @if (Auth::user()->hasPermission('dashboard-view-logs') || Auth::user()->is_admin == 1)
                                        <li class="menu-item {{ Route::is('logs.*') ? 'active' : '' }}">
                                            <a data-portal-route="logs.index" href="{{ route('logs.index') }}" class="menu-link">
                                                <i class="bi bi-list-columns-reverse" style="margin-right: 10px;"></i>
                                                <div>{{ __('locale.logs') }}</div>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                @endif
                            </li>
                </ul>
                    @endauth
                </nav>
            </aside>
            <main class="layout-page main">
                <header class="global-utility-bar" aria-label="{{ __('locale.portal.user_bar') }}">
                    <button type="button" class="utility-search-trigger" id="portal-search-toggle"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><span>{{ __('locale.portal.global_search') }}</span><kbd>Ctrl K</kbd></button>
                    <select id="portal-workspace" class="profile-mode" aria-label="{{ __('locale.portal.workspace') }}">
                        <option value="manager">{{ __('locale.portal.view_manager') }}</option>
                        <option value="technician">{{ __('locale.portal.view_technician') }}</option>
                        <option value="commerce">{{ __('locale.portal.view_commerce') }}</option>
                        <option value="adv">{{ __('locale.portal.view_adv') }}</option>
                        <option value="purchasing">{{ __('locale.portal.view_purchasing') }}</option>
                        <option value="billing">{{ __('locale.portal.view_billing') }}</option>
                        <option value="collection">{{ __('locale.portal.view_collection') }}</option>
                    </select>
                    <button type="button" class="utility-icon" id="portal-favorite-toggle" aria-pressed="false" aria-label="{{ __('locale.portal.favorite') }}" title="{{ __('locale.portal.favorite') }}" hidden><i class="bi bi-star" aria-hidden="true"></i></button>
                    <div class="dropdown"><button type="button" class="utility-icon" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('locale.portal.recent') }}" title="{{ __('locale.portal.recent') }}"><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i></button><ul class="dropdown-menu dropdown-menu-end" id="portal-recent-items"></ul></div>
                            @auth
                            @php
                                $sidebarInits = strtoupper(substr(auth()->user()->name ?? 'U', 0, 1));
                                $availableCompanies = auth()->user()->getAvailableCompanies();
                                $activeCompany = request()->hasSession() ? session('active_company_prefix') : null;
                            @endphp
                            {{-- Notification Bell --}}
                            @php
                                $totalNotifsCount = ($newTicketMessagesCount ?? 0) + ($newIntervMessagesCount ?? 0) + ($newRepairsCount ?? 0);
                            @endphp
                            <div class="dropdown">
                                <button class="utility-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                                    <i class="fa-regular fa-bell"></i>
                                    @if($totalNotifsCount > 0)
                                        <span class="utility-notification-count">{{ $totalNotifsCount > 99 ? '99+' : $totalNotifsCount }}</span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 260px;">
                                    <li class="dropdown-header text-uppercase text-muted small fw-bold">{{ __('locale.dashboard_new.notifications') }}</li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('issues.index', ['new_messages' => 1]) }}">
                                            <i class="fa-solid fa-ticket me-2 text-primary"></i>
                                            <span class="align-middle me-2">{{ __('locale.menu_tickets') }}</span>
                                            @if(($newTicketMessagesCount ?? 0) > 0)
                                                <span class="badge bg-danger ms-auto">{{ $newTicketMessagesCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('tech_intervs.index', ['new_messages' => 1]) }}">
                                            <i class="fa-solid fa-screwdriver-wrench me-2 text-primary"></i>
                                            <span class="align-middle me-2">{{ __('locale.menu_interventions') }}</span>
                                            @if(($newIntervMessagesCount ?? 0) > 0)
                                                <span class="badge bg-danger ms-auto">{{ $newIntervMessagesCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('repairs.index', ['new_items' => 1]) }}">
                                            <i class="fa-solid fa-wrench me-2 text-primary"></i>
                                            <span class="align-middle me-2">{{ __('locale.menu_repairs') }}</span>
                                            @if(($newRepairsCount ?? 0) > 0)
                                                <span class="badge bg-danger ms-auto">{{ $newRepairsCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            @if(count($availableCompanies) > 0)
                            <div class="dropdown">
                                <button class="business-entity-trigger" type="button" id="companyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-building"></i>
                                    <span>
                                        {{ $activeCompany && isset($availableCompanies[$activeCompany]) ? $availableCompanies[$activeCompany] : __('locale.all_companies') }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="companyDropdown">
                                    @if(count($availableCompanies) > 1)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center {{ !$activeCompany ? 'active' : '' }}" href="{{ route('company.swap', ['prefix' => 'all']) }}">
                                                <i class="bi bi-grid me-2"></i>
                                                <span>{{ __('locale.all_companies') }}</span>
                                            </a>
                                        </li>
                                        <li><div class="dropdown-divider"></div></li>
                                    @endif
                                    @foreach($availableCompanies as $prefix => $name)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center {{ $activeCompany === $prefix ? 'active' : '' }}" href="{{ route('company.swap', ['prefix' => $prefix]) }}">
                                                <i class="bi bi-circle-fill me-2 {{ $prefix === 'NO' ? 'text-primary' : 'text-success' }}" style="font-size: 8px;"></i>
                                                <span>{{ $name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            {{-- User Avatar Dropdown --}}
                            <div class="dropdown">
                                <button type="button" class="utility-profile" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('locale.dashboard_new.my_profile') }}">
                                    <span class="utility-profile-avatar"><i class="fa-regular fa-user" aria-hidden="true"></i></span>
                                    <span class="utility-profile-name">{{ auth()->user()->name }}</span>
                                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('user.settings.edit') }}">
                                            <div class="sidebar-avatar me-2" style="width: 32px; height: 32px; font-size: 11px;">
                                                {{ $sidebarInits }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold d-block small text-dark">{{ auth()->user()->name }}</span>
                                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('user.settings.edit') }}">
                                            <i class="bi bi-person me-2"></i>
                                            <span class="align-middle">{{ __('locale.dashboard_new.my_profile') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('user.settings.edit') }}">
                                            <i class="bi bi-gear me-2"></i>
                                            <span class="align-middle">{{ __('locale.dashboard_new.user_settings') }}</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li class="dropdown-header text-uppercase text-muted small fw-bold">{{ __('locale.dashboard_new.language') }}</li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang', ['locale' => 'fr']) }}">
                                            <i class="fi fi-fr rounded-circle fs-4 me-2"></i>
                                            <span class="align-middle">{{ __('locale.dashboard_new.french') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang', ['locale' => 'en']) }}">
                                            <i class="fi fi-us rounded-circle fs-4 me-2"></i>
                                            <span class="align-middle">{{ __('locale.dashboard_new.english') }}</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger py-2">
                                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('locale.dashboard_new.logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @endauth
                </header>
                <nav class="global-breadcrumb" aria-label="{{ __('locale.portal.home') }}">
                    <a href="{{ route('dashboard') }}">{{ __('locale.portal.home') }}</a><i class="fa-solid fa-chevron-right" aria-hidden="true"></i><strong id="portal-breadcrumb-group" hidden></strong><i id="portal-breadcrumb-separator" class="fa-solid fa-chevron-right" aria-hidden="true" hidden></i><span>{{ ($extractedTitle ?? trim(strip_tags(View::yieldContent('navbar-title') ?: View::yieldContent('title')))) ?: 'Novalyo 360' }}</span>
                </nav>
                <header class="topbar">
                    <div class="topbar-left-wrapper d-flex align-items-center gap-2">
                        <button type="button" class="btn mobile-menu-toggle portal-menu-toggle" id="mobile-menu-toggle" aria-label="{{ __('locale.portal.toggle_menu') }}" aria-controls="layout-menu" aria-expanded="false"><i class="bi bi-list" aria-hidden="true"></i></button>
                        <div class="title-block">
                            <div class="title-line">@hasSection('navbar-icon') @yield('navbar-icon') @endif @hasSection('navbar-title') @yield('navbar-title') @elseif(View::hasSection('title')) <h1>@yield('title')</h1> @else <h1>Novalyo 360</h1> @endif</div>
                            @hasSection('navbar-subtitle') <div class="subtitle">@yield('navbar-subtitle')</div> @endif
                        </div>
                    </div>
                    <div class="top-actions">
                        <div class="navbar-search-container">@yield('navbar-search')</div>
                        @yield('page-actions')
                        <button type="button" class="btn" id="portal-columns-toggle" hidden><i class="fa-solid fa-table-columns" aria-hidden="true"></i>{{ __('locale.portal.columns') }}</button>
                        <select id="portal-density" class="density-control" aria-label="{{ __('locale.portal.density') }}" hidden><option value="compact">{{ __('locale.portal.compact') }}</option><option value="comfortable" selected>{{ __('locale.portal.comfortable') }}</option><option value="large">{{ __('locale.portal.large') }}</option></select>
                        @yield('page-filters')
                    </div>
                </header>
                <section class="content content-area-main">@yield('content')</section>
            </main>
        </div>
        <div id="mobile-menu-overlay"></div>
    </div>
    <script type="application/json" id="novalyo-labels">@json(__('locale.portal'))</script>
    <dialog class="portal-dialog" id="portal-search-dialog" aria-labelledby="portal-search-title">
        <div class="portal-dialog-head"><h2 id="portal-search-title">{{ __('locale.portal.search') }}</h2><button type="button" class="portal-icon-button" data-portal-close aria-label="{{ __('locale.portal.close') }}"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <div class="portal-dialog-body"><input type="search" id="portal-search-input" class="form-control" placeholder="{{ __('locale.portal.search_placeholder') }}" aria-label="{{ __('locale.portal.search') }}" autocomplete="off"><div id="portal-search-results" class="portal-search-results"></div></div>
    </dialog>
    <dialog class="portal-dialog" id="portal-columns-dialog" aria-labelledby="portal-columns-title">
        <div class="portal-dialog-head"><h2 id="portal-columns-title">{{ __('locale.portal.columns') }}</h2><button type="button" class="portal-icon-button" data-portal-close aria-label="{{ __('locale.portal.close') }}"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <div class="portal-dialog-body"><div id="portal-column-options" class="portal-column-options"></div><button type="button" class="btn btn-outline-primary" id="portal-columns-reset">{{ __('locale.portal.reset_columns') }}</button></div>
    </dialog>
    <div id="portal-live-status" class="portal-live-status" role="status" aria-live="polite"></div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('libs/popper/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('libs/typeahead-js/typeahead.js') }}"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="{{ asset('libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('libs/swiper/swiper.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}?v=novalyo-html-v02"></script>
    <!-- Page JS -->
    <script>
        (function() {
            function decodeHtmlEntities(value) {
                if (typeof value !== 'string') {
                    return '';
                }

                if (!value.includes('&')) {
                    return value;
                }

                const parser = decodeHtmlEntities.parser || document.createElement('textarea');
                parser.innerHTML = value;
                decodeHtmlEntities.parser = parser;

                return parser.value;
            }

            function parseCategories(select) {
                if (!select) {
                    return {};
                }

                const rawValue = (select.dataset && select.dataset.categories) ||
                    select.getAttribute('data-categories') ||
                    '';

                const trimmed = rawValue.trim();
                if (!trimmed) {
                    return {};
                }

                const decoded = decodeHtmlEntities(trimmed);

                try {
                    return JSON.parse(decoded);
                } catch (error) {
                    try {
                        return Function('return (' + decoded + ')')();
                    } catch (secondaryError) {
                        console.warn('Unable to parse category options', error, {
                            raw: rawValue
                        });
                    }

                    return {};
                }
            }

            function normalizeKey(value) {
                if (value === undefined || value === null) {
                    return '';
                }

                return String(value);
            }

            function resolveCategoryOptions(entry) {
                if (Array.isArray(entry)) {
                    return {
                        options: entry,
                        placeholder: undefined
                    };
                }

                if (entry && typeof entry === 'object') {
                    const options = Array.isArray(entry.options) ? entry.options : [];
                    const placeholder = typeof entry.placeholder === 'string' ? entry.placeholder : undefined;

                    return {
                        options,
                        placeholder
                    };
                }

                return {
                    options: [],
                    placeholder: undefined
                };
            }

            function applyCategoryOptions(typeSelect, categorySelect, categories, placeholder, preserveSelection) {
                const key = normalizeKey(typeSelect?.value ?? '');
                const primary = resolveCategoryOptions(categories[key]);
                let options = primary.options;
                let derivedPlaceholder = primary.placeholder;

                if (!options.length && key !== '') {
                    const fallback = resolveCategoryOptions(categories['']);
                    options = fallback.options;

                    if (!derivedPlaceholder) {
                        derivedPlaceholder = fallback.placeholder;
                    }
                }

                const effectivePlaceholder = placeholder || derivedPlaceholder || '';
                const storedSelection = categorySelect.dataset.selected ?? '';
                const previousValue = preserveSelection ? (storedSelection || categorySelect.value || '') : '';

                categorySelect.innerHTML = '';

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.textContent = effectivePlaceholder;
                categorySelect.appendChild(placeholderOption);

                let selectedValue = previousValue;

                if (selectedValue && !options.some(option => String(option.id ?? option.value ?? '') === String(
                        selectedValue))) {
                    selectedValue = '';
                }

                // Group options by equipment_type_name
                const groups = {};
                const noGroup = [];

                options.forEach(option => {
                    const groupName = option.equipment_type_name;
                    if (groupName) {
                        if (!groups[groupName]) {
                            groups[groupName] = [];
                        }
                        groups[groupName].push(option);
                    } else {
                        noGroup.push(option);
                    }
                });

                const groupKeys = Object.keys(groups).sort();

                function createOptionElement(option, selectedVal) {
                    const optionValue = option.id ?? option.value ?? '';
                    const optionLabel = option.name ?? option.label ?? option.text ?? '';
                    const optionEl = document.createElement('option');
                    optionEl.value = optionValue;
                    optionEl.textContent = optionLabel;

                    if (String(optionValue) === String(selectedVal)) {
                        optionEl.selected = true;
                    }
                    return optionEl;
                }

                if (groupKeys.length > 0) {
                    // Render grouped options
                    groupKeys.forEach(groupName => {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = groupName;
                        groups[groupName].forEach(option => {
                            const optionEl = createOptionElement(option, selectedValue);
                            optgroup.appendChild(optionEl);
                        });
                        categorySelect.appendChild(optgroup);
                    });

                    // Render ungrouped options under "Autres"
                    if (noGroup.length > 0) {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = 'Autres';
                        noGroup.forEach(option => {
                            const optionEl = createOptionElement(option, selectedValue);
                            optgroup.appendChild(optionEl);
                        });
                        categorySelect.appendChild(optgroup);
                    }
                } else {
                    // Render all options flat
                    options.forEach(option => {
                        const optionEl = createOptionElement(option, selectedValue);
                        categorySelect.appendChild(optionEl);
                    });
                }

                if (!selectedValue) {
                    categorySelect.selectedIndex = 0;
                }

                const shouldDisable = !key || options.length === 0;
                categorySelect.disabled = shouldDisable;

                if (shouldDisable) {
                    categorySelect.dataset.selected = '';
                } else {
                    categorySelect.dataset.selected = categorySelect.value;
                }
            }

            const typeChangeHandlers = new WeakMap();

            function bindTypeChange(typeSelect, categorySelect, categories, placeholder) {
                let handlers = typeChangeHandlers.get(typeSelect);

                if (!handlers) {
                    handlers = [];
                    typeChangeHandlers.set(typeSelect, handlers);
                }

                if (handlers.some(entry => entry.category === categorySelect)) {
                    return;
                }

                const handler = () => {
                    applyCategoryOptions(typeSelect, categorySelect, categories, placeholder, false);
                };

                handlers.push({
                    category: categorySelect,
                    handler
                });
                typeSelect.addEventListener('change', handler);
            }

            function initCategoryDropdown(root) {
                const scope = root || document;
                const categorySelects = scope.querySelectorAll('select[data-categories]');

                categorySelects.forEach(categorySelect => {
                    const context = categorySelect.closest('form') || scope;
                    let typeSelect = context.querySelector('select[name="issue_type_id"]');

                    if (!typeSelect && context !== document) {
                        typeSelect = document.querySelector('select[name="issue_type_id"]');
                    }

                    if (!typeSelect) {
                        return;
                    }

                    const categories = parseCategories(categorySelect);
                    const placeholder = categorySelect.dataset.placeholder || '';
                    const preserve = categorySelect.dataset.enhanced === '1';

                    applyCategoryOptions(typeSelect, categorySelect, categories, placeholder, true);

                    if (preserve) {
                        return;
                    }

                    categorySelect.dataset.enhanced = '1';

                    bindTypeChange(typeSelect, categorySelect, categories, placeholder);

                    categorySelect.addEventListener('change', () => {
                        categorySelect.dataset.selected = categorySelect.value;
                    });
                });
            }

            const runIssueCategoryInitialization = (target) => {
                let scope = target || document;

                const isNodeList = typeof NodeList !== 'undefined' && scope instanceof NodeList;
                if (isNodeList || Array.isArray(scope)) {
                    scope = scope.length ? scope[0] : document;
                }

                const isElement = typeof Element !== 'undefined' && scope instanceof Element;
                const isFragment = typeof DocumentFragment !== 'undefined' && scope instanceof DocumentFragment;
                if (!isElement && scope !== document && !isFragment) {
                    scope = document;
                }

                try {
                    initCategoryDropdown(scope);
                } catch (error) {
                    console.error('Failed to initialize issue category dropdowns', error);
                }
            };

            window.initIssueCategoryDropdown = runIssueCategoryInitialization;
            window.refreshIssueCategoryDropdowns = runIssueCategoryInitialization;

            document.addEventListener('issue:categories:init', (event) => {
                const detailScope = event && event.detail ? event.detail.scope : undefined;
                const targetScope = detailScope || (event ? event.target : null) || document;
                runIssueCategoryInitialization(targetScope);
            });

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    runIssueCategoryInitialization(document);
                });
            } else {
                runIssueCategoryInitialization(document);
            }
        })();
    </script>
    <script>
        document.addEventListener('click', function(event) {
            const dismissButton = event.target.closest('[data-bs-dismiss="modal"]');
            if (!dismissButton) return;
            event.preventDefault();
            const modalEl = dismissButton.closest('.modal');
            if (!modalEl) return;
            modalEl.classList.remove('show');
            modalEl.setAttribute('aria-hidden', 'true');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        });
    </script>
    <script>
        (function() {
            function adjustBadgeContrast() {
                const badges = document.querySelectorAll('.badge');
                badges.forEach(badge => {
                    const style = window.getComputedStyle(badge);
                    const bgColor = style.backgroundColor;
                    const textColor = style.color;

                    // Helper to parse rgb/rgba strings
                    function parseRgb(color) {
                        const match = color.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
                        return match ? {
                            r: parseInt(match[1]),
                            g: parseInt(match[2]),
                            b: parseInt(match[3])
                        } : null;
                    }

                    const bg = parseRgb(bgColor);
                    const fg = parseRgb(textColor);

                    if (!bg || !fg) return;

                    // Calculate relative luminance (W3C formula)
                    function getLuminance(c) {
                        const a = [c.r, c.g, c.b].map(v => {
                            v /= 255;
                            return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
                        });
                        return a[0] * 0.2126 + a[1] * 0.7152 + a[2] * 0.0722;
                    }

                    const lum1 = getLuminance(bg);
                    const lum2 = getLuminance(fg);

                    const ratio = (Math.max(lum1, lum2) + 0.05) / (Math.min(lum1, lum2) + 0.05);

                    // If contrast ratio is low (WCAG AA for small text is 4.5:1, but these are small decorative badges)
                    // Let's use 3:1 as a minimum for these specific badges since they are mostly light-themed
                    if (ratio < 3.5) {
                        // Darken the text color
                        // We can just set a darker version of the current foreground color
                        // or explicitly darken it by 30-50%
                        const darkened =
                            `rgb(${Math.floor(fg.r * 0.6)}, ${Math.floor(fg.g * 0.6)}, ${Math.floor(fg.b * 0.6)})`;
                        badge.style.setProperty('color', darkened, 'important');
                    }
                });
            }

            // Run on load and whenever the DOM might change significantly (optional)
            window.addEventListener('load', adjustBadgeContrast);
            // Also handle dynamic content if needed (e.g. after AJAX loads)
            const observer = new MutationObserver(adjustBadgeContrast);
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        })();
    </script>

    <script>
        $(document).on('select2:open', function() {
            setTimeout(function() {
                const searchField = document.querySelector('.select2-container--open .select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            }, 10);
        });
    </script>

    <!-- Flatpickr JS -->
    <script src="{{ asset('libs/flatpickr/flatpickr.js') }}"></script>
    <script>
        (function() {
            function initGlobalDatePickers(root) {
                const scope = root || document;
                if (typeof flatpickr === 'undefined') return;

                // Date inputs: display dd/mm/YYYY (d/m/Y)
                scope.querySelectorAll('input[type="date"], input.flatpickr-date').forEach(function(el) {
                    if (!el._flatpickr && !el.dataset.noFlatpickr) {
                        flatpickr(el, {
                            dateFormat: "Y-m-d",
                            altInput: true,
                            altFormat: "d/m/Y",
                            allowInput: true
                        });
                    }
                });

                // Datetime inputs: display dd/mm/YYYY HH:ii (d/m/Y H:i)
                scope.querySelectorAll('input[type="datetime-local"], input.flatpickr-datetime').forEach(function(el) {
                    if (!el._flatpickr && !el.dataset.noFlatpickr) {
                        flatpickr(el, {
                            enableTime: true,
                            time_24hr: true,
                            dateFormat: "Y-m-d\\TH:i",
                            altInput: true,
                            altFormat: "d/m/Y H:i",
                            allowInput: true
                        });
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => initGlobalDatePickers());
            } else {
                initGlobalDatePickers();
            }

            // Support dynamically added inputs / modals
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) {
                            initGlobalDatePickers(node);
                        }
                    });
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });
        })();
    </script>

    <!-- Mobile Menu Popup Controller -->


    @yield('scripts')
    @stack('scripts')
</body>

</html>
