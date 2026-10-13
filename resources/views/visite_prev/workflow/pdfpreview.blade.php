@extends('layouts.app') @section('title', 'Visit Report #' . $vp_visit->id)
@push('styles')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"
		integrity="sha384-GPAzSuZc0kFvdIev6wm9zg8gnafE8tLso7rsAYQfc9hAdWCpOcpcNI5W9lWkYcsd" crossorigin="anonymous">
	<style>
		body {
			font-family: DejaVu Sans, sans-serif;
			font-size: 12px;
			line-height: 1.4;
			background: #f5f7fa;
		}

		h1 {
			font-size: 28px;
			font-weight: 700;
			letter-spacing: -0.5px;
		}

		h2,
		h3 {
			margin-bottom: 8px;
			margin-top: 16px !important;
			font-weight: 700;
			color: #1f2937;
		}

		.report-header {
			background: linear-gradient(120deg, #0d6efd 0%, #4f46e5 50%, #16a4d8 100%);
			color: #fff;
			border-radius: 16px;
			padding: 24px;
			box-shadow: 0 10px 40px rgba(13, 110, 253, 0.25);
		}

		.report-meta {
			gap: 16px;
		}

		.report-meta .meta-pill {
			background: rgba(255, 255, 255, 0.12);
			border: 1px solid rgba(255, 255, 255, 0.25);
			color: #fff;
			border-radius: 12px;
			padding: 10px 14px;
			font-weight: 600;
			display: inline-flex;
			align-items: center;
			gap: 8px;
		}

		.report-meta .meta-pill .label {
			opacity: 0.8;
			font-weight: 500;
		}

		.workflow-card {
			border: none;
			border-radius: 16px;
			box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
			overflow: hidden;
		}

		.workflow-card .card-header {
			background: #ffffff;
			border-bottom: 1px solid #eef2f7;
		}

		.workflow-card .card-body {
			background: #fff;
		}

		.nav-pills.workflow-tabs {
			background: #f8fafc;
			border-radius: 12px;
			padding: 10px;
			gap: 8px;
			flex-wrap: wrap;
		}

		.nav-pills.workflow-tabs .nav-link {
			border-radius: 10px;
			color: #334155;
			font-weight: 600;
			padding: 10px 14px;
			border: 1px solid #e2e8f0;
			background: #fff;
			box-shadow: 0 2px 6px rgba(51, 65, 85, 0.05);
			transition: all 0.15s ease-in-out;
		}

		.nav-pills.workflow-tabs .nav-link:hover {
			color: #0d6efd;
			border-color: #bfdbfe;
			box-shadow: 0 6px 18px rgba(13, 110, 253, 0.15);
		}

		.nav-pills.workflow-tabs .nav-link.active {
			background: linear-gradient(120deg, #0d6efd 0%, #4f46e5 100%);
			color: #fff;
			border-color: transparent;
			box-shadow: 0 10px 24px rgba(79, 70, 229, 0.35);
		}

		.section {
			margin-bottom: 20px;
			page-break-inside: avoid;
			margin-top: 12px;
			padding: 16px;
			border: 1px solid #eef2f7;
			border-radius: 12px;
			background: #f8fafc;
		}

		.section p {
			margin-bottom: 6px;
			color: #475569;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 8px;
			background: #fff;
			border-radius: 12px;
			overflow: hidden;
		}

		th {
			background: #0d6efd;
			color: #fff;
			font-weight: 700;
			border: none;
		}

		th,
		td {
			border: 1px solid #e2e8f0;
			padding: 8px 10px;
		}

		tr:nth-child(even) td {
			background: #f8fafc;
		}

		.signature,
		.image-preview {
			margin-top: 5px;
			max-height: 140px;
			border: 1px solid #e2e8f0;
			border-radius: 10px;
		}

		img.image-preview,
		img.signature {
			display: block;
			max-width: 100%;
			height: auto;
			margin-bottom: 10px;
			box-shadow: 0 6px 16px rgba(15, 23, 42, 0.12);
		}

		.tab-pane {
			animation: fadeIn 0.25s ease-in-out;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(6px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
	</style>
@endpush

@section('content')
	<div class="container py-4">
		<div class="report-header mb-4">
			<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
				<div>
					<p class="text-uppercase mb-1" style="letter-spacing: 1px; opacity: 0.8;">Preventive Visit Report</p>
					<h1 class="mb-0">Report #{{ $vp_visit->id }}</h1>
				</div>
				<div class="d-flex flex-wrap report-meta">
					<div class="meta-pill"><span class="label">Client</span> <span>{{ $vp_visit->client->raison_sociale ?? 'N/A' }}</span></div>
					<div class="meta-pill"><span class="label">Installer</span> <span>{{ $vp_visit->installer->name ?? 'N/A' }}</span></div>
					<div class="meta-pill"><span class="label">Date</span> <span>{{ format_date($vp_visit->created_at, 'short') }}</span></div>
					@if ($vp_visit->status)
						<div class="meta-pill">
							<span class="label">{{ __('visite_prev.status') }}</span>
							<span class="badge" style="background-color: {{ $vp_visit->status->color }}; color: {{ $vp_visit->status->text_color }};">
								{{ $vp_visit->status->translated_name }}
							</span>
						</div>
					@endif
				</div>
			</div>
		</div>

		<div class="card workflow-card">
			<div class="card-header">
				<ul class="nav nav-pills workflow-tabs" id="vpVisitStepsTabs" role="tablist">
					@for ($i = 1; $i <= 14; $i++)
						<li class="nav-item" role="presentation">
							<button class="nav-link {{ $i === 1 ? 'active' : '' }}" id="step{{ $i }}-tab" data-bs-toggle="tab" data-bs-target="#step{{ $i }}" type="button" role="tab"
								aria-controls="step{{ $i }}" aria-selected="{{ $i === 1 ? 'true' : 'false' }}">Step {{ $i }}</button>
						</li>
					@endfor
				</ul>
			</div>

			<div class="card-body">
				<div class="tab-content" id="vpVisitStepsContent">
					{{-- Step 1: Basic Info --}}
					<div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
						<div class="section">
							<h2>Step 1: Client & Installer</h2>
							<p><strong>Client:</strong> {{ $vp_visit->client->raison_sociale ?? 'N/A' }}</p>
							<p><strong>Installer:</strong> {{ $vp_visit->installer->name ?? 'N/A' }}</p>
							<p><strong>Date:</strong> {{ format_date($vp_visit->created_at, 'short') }}</p>
						</div>
					</div>

					{{-- Step 2: Checklist --}}
					<div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
						<div class="section">
							<h2>Step 2: Checklist Items</h2>
							<table class="w-100">
								<thead>
									<tr>
										<th>#</th>
										<th>Action</th>
										<th>Done</th>
										<th>Comments</th>
										<th>To Do</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vp_visit->checklistItems as $item)
										<tr>
											<td>{{ $item->order_number }}</td>
											<td>{{ $item->action }}</td>
											<td>{{ $item->done ? '✔' : '✘' }}</td>
											<td>{{ $item->comments }}</td>
											<td>{{ $item->todo }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Step 3: Telecom Equipments --}}
					<div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
						<div class="section">
							<h2>Step 3: Telecom Equipments</h2>
							@foreach ($vp_visit->telecomEquipments as $equip)
								<p>
									<strong>Type:</strong> {{ $equip->type }},
									<strong>Make:</strong> {{ $equip->make }},
									<strong>Model:</strong> {{ $equip->model }},
									<strong>Qty:</strong> {{ $equip->qty }},
									<strong>Place:</strong> {{ $equip->place }}
								</p>
								@foreach ($equip->images ?? [] as $img)
									<a href="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" class="glightbox" data-gallery="signature-gallery">
										<img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" class="image-preview" alt="Equipment Image">
									</a>
								@endforeach
							@endforeach
						</div>
					</div>

					{{-- Step 4: Patient Call Equipments --}}
					<div class="tab-pane fade" id="step4" role="tabpanel" aria-labelledby="step4-tab">
						<div class="section">
							<h2>Step 4: Patient Call Equipments</h2>
							@foreach ($vp_visit->patientCallEquipments as $equip)
								<p>
									<strong>Type:</strong> {{ $equip->type }},
									<strong>Make:</strong> {{ $equip->make }},
									<strong>Model:</strong> {{ $equip->model }},
									<strong>Qty:</strong> {{ $equip->qty }},
									<strong>Place:</strong> {{ $equip->place }}
								</p>
								@foreach ($equip->images ?? [] as $img)
									<a href="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" class="glightbox" data-gallery="signature-gallery">
										<img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" class="image-preview" alt="Equipment Image">
									</a>
								@endforeach
							@endforeach
						</div>
					</div>

					{{-- Step 5: DECT Controls --}}
					<div class="tab-pane fade" id="step5" role="tabpanel" aria-labelledby="step5-tab">
						<div class="section">
							<h3>Step 5: DECT Controls</h3>
							<table class="w-100">
								<thead>
									<tr>
										<th>#</th>
										<th>Reference</th>
										<th>Status</th>
										<th>To Do</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vp_visit->dectControls as $dect)
										<tr>
											<td>{{ $dect->numero }}</td>
											<td>{{ $dect->reference }}</td>
											<td>
												Cover: {{ $dect->cover_hs ? 'X' : '-' }},
												Screen: {{ $dect->screen_hs ? 'X' : '-' }},
												Battery: {{ $dect->battery_hs ? 'X' : '-' }},
												Mic: {{ $dect->mic_hs ? 'X' : '-' }},
												Speaker: {{ $dect->speaker_hs ? 'X' : '-' }}
											</td>
											<td>{{ $dect->todo }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Step 6: Interrupt Repairs --}}
					<div class="tab-pane fade" id="step6" role="tabpanel" aria-labelledby="step6-tab">
						<div class="section">
							<h3>Step 6: Repairs - Interrupts</h3>
							<table class="w-100">
								<thead>
									<tr>
										<th>Equipment</th>
										<th>Disjoncteur</th>
										<th>Type</th>
										<th>Situation</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vp_visit->repairInterrupts as $repair)
										<tr>
											<td>{{ $repair->equipment }}</td>
											<td>{{ $repair->disjoncteur ? 'Yes' : 'No' }}</td>
											<td>{{ $repair->type }}</td>
											<td>{{ $repair->situation }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Step 7: Elevator Repairs --}}
					<div class="tab-pane fade" id="step7" role="tabpanel" aria-labelledby="step7-tab">
						<div class="section">
							<h3>Step 7: Elevator Repairs</h3>
							@foreach ($vp_visit->repairElevators as $elevator)
								<p><strong>{{ $elevator->elevator }}</strong> (Line: {{ $elevator->linenumber }}) — PABX: {{ $elevator->passes_by_pabx ? 'Yes' : 'No' }}</p>
							@endforeach
						</div>
					</div>

					{{-- Step 8: Help Line Repairs --}}
					<div class="tab-pane fade" id="step8" role="tabpanel" aria-labelledby="step8-tab">
						<div class="section">
							<h3>Step 8: Help Line Repairs</h3>
							@foreach ($vp_visit->repairHelpLines as $line)
								<p><strong>{{ $line->name }}</strong> (Line: {{ $line->linenumber }}) — PABX: {{ $line->passes_by_pabx ? 'Yes' : 'No' }}</p>
							@endforeach
						</div>
					</div>

					{{-- Step 9: Inputs --}}
					<div class="tab-pane fade" id="step9" role="tabpanel" aria-labelledby="step9-tab">
						<div class="section">
							<h3>Step 9: Input Devices</h3>
							<table class="w-100">
								<thead>
									<tr>
										<th>Name</th>
										<th>Model</th>
										<th>Batteries</th>
										<th>Voltages</th>
										<th>Flags</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vp_visit->inputs as $input)
										<tr>
											<td>{{ $input->name }}</td>
											<td>{{ $input->model }}</td>
											<td>{{ $input->number_of_batteries }}</td>
											<td>
												Charge: {{ $input->charge_tension }}V,
												Batt: {{ $input->battery_tension }}V<br>
												After cut: {{ $input->tension_after_power_cut }}V,
												After 30m: {{ $input->tension_after_30mins_power_cut }}V
											</td>
											<td>
												Change: {{ $input->needs_change ? 'Yes' : 'No' }},
												Battery: {{ $input->battery_needs_change ? 'Yes' : 'No' }}
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Step 10: Tech Training --}}
					<div class="tab-pane fade" id="step10" role="tabpanel" aria-labelledby="step10-tab">
						<div class="section">
							<h3>Step 10: Technician Training</h3>
							<p><strong>Common Points Discussed:</strong> {!! $vp_visit->common_points_discussed !!}</p>
							@foreach ($vp_visit->trainingTechs as $tech)
								<p>{{ $tech->first_name }} {{ $tech->last_name }} ({{ $tech->position }})</p>
								@if ($tech->signature)
									<a href="{{ asset($tech->signature) }}" class="glightbox" data-gallery="signature-gallery">
										<img src="{{ asset($tech->signature) }}" class="signature" alt="Signature">
									</a>
								@endif
							@endforeach
						</div>
					</div>

					{{-- Step 11: Admin Training --}}
					<div class="tab-pane fade" id="step11" role="tabpanel" aria-labelledby="step11-tab">
						<div class="section">
							<h3>Step 11: Admin Training</h3>
							<p><strong>Common Points Discussed:</strong> {!! $vp_visit->common_points_discussed_admin !!}</p>
							@foreach ($vp_visit->trainingAdmins as $admin)
								<p>{{ $admin->first_name }} {{ $admin->last_name }} ({{ $admin->position }})</p>
								@if ($admin->signature)
									<a href="{{ asset($admin->signature) }}" class="glightbox" data-gallery="signature-gallery">
										<img src="{{ asset($admin->signature) }}" class="signature" alt="Signature">
									</a>
								@endif
							@endforeach
						</div>
					</div>

					{{-- Step 12: Nurses Training --}}
					<div class="tab-pane fade" id="step12" role="tabpanel" aria-labelledby="step12-tab">
						<div class="section">
							<h3>Step 12: Nurse Training</h3>
							<p><strong>Common Points Discussed:</strong> {!! $vp_visit->common_points_discussed_nurse !!}</p>
							@foreach ($vp_visit->trainingNurses as $nurse)
								<p>{{ $nurse->first_name }} {{ $nurse->last_name }} ({{ $nurse->position }})</p>
								@if ($nurse->signature)
									<a href="{{ asset($nurse->signature) }}" class="glightbox" data-gallery="signature-gallery">
										<img src="{{ asset($nurse->signature) }}" class="signature" alt="Signature">
									</a>
								@endif
							@endforeach
						</div>
					</div>

					{{-- Step 13: Config Backup --}}
					<div class="tab-pane fade" id="step13" role="tabpanel" aria-labelledby="step13-tab">
						<div class="section">
							<h3>Step 13: Config Backup</h3>
							<table class="w-100">
								<thead>
									<tr>
										<th>Name</th>
										<th>File</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vp_visit->configBackups as $backup)
										<tr>
											<td>{{ $backup->name }}</td>
											<td>{{ $backup->filename }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					{{-- Step 14: Stock --}}
					<div class="tab-pane fade" id="step14" role="tabpanel" aria-labelledby="step14-tab">
						<div class="section">
							<h3>Step 14: Stock Inventory</h3>
							<table class="w-100">
								<thead>
									<tr>
										<th>Category</th>
										<th>Type</th>
										<th>Make</th>
										<th>Model</th>
										<th>Qty</th>
										<th>References</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($vp_visit->stocks as $stock)
										<tr>
											<td>{{ $stock->category }}</td>
											<td>{{ $stock->type }}</td>
											<td>{{ $stock->make }}</td>
											<td>{{ $stock->model }}</td>
											<td>{{ $stock->qty }}</td>
											<td>{{ $stock->references }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="height: 150px;"></div>
	<!-- GLightbox JS -->
	<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"
		integrity="sha384-MZZbZ6RXJudK43v1qY1zOWKOU2yfeBPatuFoKyHAaAgHTUZhwblRTc9CphTt4IGQ" crossorigin="anonymous"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const lightbox = GLightbox({
				selector: '.glightbox'
			});
		});
	</script>

@endsection
