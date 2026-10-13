@extends('layouts.app') @section('content') <style>
		.form_title {
			background-color: var(--bs-purple) !important;
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		.address-color {
			color: var(--bs-purple) !important;
			text-align: justify;
			text-justify: inter-word;
		}

		.address-title {
			text-align: center;
			text-justify: inter-word;
		}

		.repair-print .print-area {
			background-color: #ffffff;
			padding: 2.5rem 3rem;
			max-width: 297mm;
			min-height: 210mm;
			margin: 0 auto;
		}

		.repair-print .print-logo {
			max-width: 300px;
			height: auto;
		}

		.repair-print .info-value {
			display: block;
			padding: 0.35rem 0;
			font-size: 0.95rem;
			line-height: 1.4;
			white-space: pre-wrap;
			color: #212529;
		}

		.repair-print .print-header h1 {
			font-size: 1.5rem;
			letter-spacing: 0.05em;
		}

		.repair-print .subheading {
			font-size: 0.75rem;
			letter-spacing: 0.08em;
			text-transform: uppercase;
			color: #6c757d;
			margin-bottom: 0.25rem;
		}

		.repair-print .print-value {
			display: block;
			font-size: 0.95rem;
			line-height: 1.5;
			color: #212529;
			white-space: pre-wrap;
			min-height: 1.5rem;
		}

		.repair-print .print-value.is-multiline {
			min-height: 4.5rem;
		}

		.repair-print .client-summary {
			display: grid;
			gap: 1.5rem;
			align-items: start;
		}

		.repair-print .client-summary .client-main,
		.repair-print .client-summary .client-address,
		.repair-print .client-summary .delivery-address {
			min-width: 0;
		}

		@media (min-width: 768px) {
			.repair-print .client-summary {
				grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr) minmax(0, 1fr);
			}

			.repair-print .client-summary .client-main {
				grid-column: 1 / span 1;
			}
		}

		.repair-equipment-table th {
			vertical-align: middle;
			font-size: 0.65rem;
			padding: 0.3rem 0.45rem;
			text-transform: uppercase;
		}

		.repair-equipment-table td {
			vertical-align: middle;
			font-size: 0.7rem;
			padding: 0.3rem 0.45rem;
		}

		.repair-equipment-table,
		.repair-equipment-table th,
		.repair-equipment-table td {
			border: 1px solid #dee2e6;
		}

		.repair-equipment-table {
			border-collapse: collapse;
			width: 100%;
		}

		.repair-equipment-table .static-value {
			display: block;
			padding: 0;
			font-size: 0.7rem;
			line-height: 1.3;
			color: #212529;
			background: transparent;
			border: none;
		}

		.equipment-page {
			margin-bottom: 1.5rem;
		}

		.repair-equipment-table .static-value:not(:last-child) {
			margin-bottom: 0.15rem;
		}

		.repair-equipment-table .print-checkbox {
			font-size: 1rem;
			line-height: 1;
		}

		@media print {
			body {
				background: #fff !important;
			}

			.layout-menu,
			.layout-navbar,
			.layout-page>.content-wrapper>.container-xxl,
			.layout-page>footer,
			.no-print {
				display: none !important;
			}

			.layout-page {
				padding-left: 0 !important;
			}

			.repair-print .print-area {
				border: none;
				box-shadow: none;
				padding: 0;
				max-width: 100%;
				min-height: auto;
				display: block;
				width: 100%;
			}

			.table-responsive {
				overflow: visible !important;
			}

			header.print-header,
			main.print-body {
				background-color: #fff;
			}

			header.print-header {
				/* padding: 0.75rem 2rem 1.25rem; */
				page-break-after: avoid;
				page-break-inside: avoid;
			}

			.repair-equipment-table thead {
				display: table-header-group;
			}

			.repair-equipment-table tbody {
				display: table-row-group;
			}

			.repair-equipment-table tr {
				page-break-inside: avoid;
			}

			.equipment-page {
				page-break-inside: avoid;
			}

			.equipment-page:not(:last-child) {
				page-break-after: always;
			}

			.repair-print .client-summary {
				grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr) minmax(0, 1fr);
			}

			.repair-print .client-summary .client-main {
				grid-column: 1 / span 1;
			}

			@page {
				size: A4 landscape;
				margin: 5mm 18mm 15mm;
			}
		}
	</style>
	@php
		$printFormData = $printFormData ?? [];
		$resolvedDate = $printFormData['form_date'] ?? now()->format('Y-m-d');
		$resolvedContactDetails = $printFormData['contact_details'] ?? $contactDetails;
		$resolvedClientAddress = $printFormData['client_address'] ?? $clientAddress;
		$resolvedDeliveryAddress = $printFormData['delivery_address'] ?? $clientAddress;
		
		$allEquipmentData = $printFormData['equipment'] ?? [];
		$displayEquipments = collect();
		
		// 1. Process existing equipments linked to the repair
		foreach ($repair->equipmentRepairs as $er) {
			$overrides = $allEquipmentData[$er->id] ?? null;
			$displayEquipments->push((object)[
				'id' => $er->id,
				'type' => $overrides['type'] ?? ($er->equipment?->equipmentType?->name ?? ''),
				'serial_number' => $overrides['serial_number'] ?? ($er->serial_number ?? ''),
				'reference' => $overrides['reference'] ?? ($er->equipment?->reference ?? ''),
				'name' => $overrides['name'] ?? ($er->equipment?->name ?? ''),
				'checklist' => $overrides,
			]);
		}
		
		// 2. Process extra (ad-hoc) equipments
		$existingIds = $repair->equipmentRepairs->pluck('id')->toArray();
		foreach ($allEquipmentData as $key => $data) {
			if (!in_array($key, $existingIds)) {
				$displayEquipments->push((object)[
					'id' => $key,
					'type' => $data['type'] ?? '',
					'serial_number' => $data['serial_number'] ?? '',
					'reference' => $data['reference'] ?? '',
					'name' => $data['name'] ?? '',
					'checklist' => $data,
				]);
			}
		}
		
		$equipmentChunks = $displayEquipments->chunk(4);
	@endphp

	<div class="container py-4 repair-print">
		@if (session('status'))
			<div
				class="alert alert-success no-print">{{ session('status') }}</div>
		@endif
		<div class="d-flex justify-content-between align-items-center mb-4 no-print">
			<div>
				<a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
					<i class="bi bi-arrow-left"></i> {{ __('locale.back') }} </a>
			</div>
			<div class="d-flex gap-2">
				<a href="{{ route('repairs.print-form', $repair) }}" target="_blank" class="btn btn-outline-primary">
					<i class="bi bi-pencil"></i> {{ __('locale.btn_edit_form') }} </a>
				<button type="button" class="btn btn-primary" onclick="window.print()">
					<i class="bi bi-printer"></i> {{ __('locale.btn_print') }} </button>
			</div>
		</div>
		<div class="print-area mx-auto">
			<header class="print-header">
				<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
					<div class="text-start">
						<img src="{{ asset('images/logo.png') }}" alt="Logo" class="print-logo mb-2">
						<div class="text-uppercase fw-semibold small address-title">Adresse unique pour le renvoi des réparations</div>
						<div class="small text-uppercase address-color">NOVALYO - ZAC des castors - 38150 Salaise sur sanne</div>
					</div>
					<h4 class="text-center text-uppercase h5 mb-0 flex-grow-1 mt-12 text-white form_title">{{ __('locale.repair_form') }}</h4>
					<div class="text-center mt-12" style="min-width: 200px;">
						<div class="subheading fw-semibold">{{ __('locale.lbl_date') }}</div>
						<span class="print-value">{{ $resolvedDate ? format_date($resolvedDate, 'short') : '' }}</span>
					</div>
				</div>
				<div class="client-summary">
					<div class="client-main">
						@if (filled($clientInformation))
							<div class="subheading fw-semibold mt-3">{{ __('locale.lbl_client_information') }}</div>
							<span class="info-value">{{ $clientInformation }}</span>
						@endif
						<div class="subheading fw-semibold mt-3">{{ __('locale.lbl_client_contact') }}</div>
						<span class="info-value">{{ trim(collect([$contact?->civility, $contact?->prenom, $contact?->nom])->filter()->implode(' ')) }}</span>
						<div class="subheading fw-semibold mt-3">{{ __('locale.lbl_contact_details') }}</div>
						<span class="print-value">{{ $resolvedContactDetails }}</span>
					</div>
					<div class="client-address">
						<div class="subheading fw-semibold mt-3">{{ __('locale.lbl_client_address') }}</div>
						<span class="print-value is-multiline">{{ $resolvedClientAddress }}</span>
					</div>
					<div class="delivery-address">
						<div class="subheading fw-semibold mt-3">{{ __('locale.lbl_delivery_address') }}</div>
						<span class="print-value is-multiline">{{ $resolvedDeliveryAddress }}</span>
					</div>
				</div>
				<h4 class="text-center text-uppercase h5 flex-grow-1 text-white form_title mt-2">{{ __('locale.lbl_repair_do_not_send') }}</h4>
			</header>
			<main class="print-body">
				@if ($equipmentChunks->isNotEmpty())
					@foreach ($equipmentChunks as $equipmentChunk)
						<div class="table-responsive equipment-page">
							<table class="table table-sm align-middle repair-equipment-table">
								<thead class="table-light">
									<tr>
										<th scope="col" class="text-center">{{ __('locale.lbl_type') }}</th>
										<th scope="col" class="text-center">{{ __('locale.serial_number') }}</th>
										<th scope="col" class="text-center">{{ __('locale.internal_number') }}</th>
										<th scope="col" class="text-center">{{ __('locale.lbl_name') }}</th>
										@foreach ($equipmentChecklistFields as $label)
											<th scope="col" class="text-center">{{ $label }}</th>
										@endforeach
										<th scope="col" class="text-center">{{ __('locale.other') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($equipmentChunk as $equipment)
										@php $checklist = $equipment->checklist ?? []; @endphp <tr>
											<td>
												<span class="static-value">{{ $equipment->type }}</span>
											</td>
											<td>
												<span class="static-value">{{ $equipment->serial_number }}</span>
											</td>
											<td>
												<span class="static-value">{{ $equipment->reference }}</span>
											</td>
											<td>
												<span class="static-value">{{ \Illuminate\Support\Str::limit($equipment->name ?? '', 20, '...') }}</span>
											</td>
											@foreach ($equipmentChecklistFields as $fieldKey => $label)
												@php $isChecked = (bool) ($checklist[$fieldKey] ?? false); @endphp <td class="text-center">
													<span class="print-checkbox">{{ $isChecked ? '☑' : '☐' }}</span>
												</td>
											@endforeach
											<td>
												<span class="static-value">{{ $checklist['other'] ?? '' }}</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@endforeach
				@else
					<div class="table-responsive">
						<table class="table table-sm align-middle repair-equipment-table">
							<thead class="table-light">
								<tr>
									<th scope="col" class="text-center">{{ __('locale.lbl_type') }}</th>
									<th scope="col" class="text-center">{{ __('locale.serial_number') }}</th>
									<th scope="col" class="text-center">{{ __('locale.internal_number') }}</th>
									<th scope="col" class="text-center">{{ __('locale.lbl_name') }}</th>
									@foreach ($equipmentChecklistFields as $label)
										<th scope="col" class="text-center">{{ $label }}</th>
									@endforeach
									<th scope="col" class="text-center">{{ __('locale.other') }}</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="{{ 5 + count($equipmentChecklistFields) }}" class="text-center fst-italic">{{ __('locale.no_equipment_linked_to_repair') }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				@endif
			</main>
		</div>
</div> @endsection
