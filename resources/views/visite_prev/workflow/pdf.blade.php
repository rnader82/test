<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>{{ __('locale.visite_prev.preventive_visit_report') }} #{{ $vp_visit->id }}</title>
		<style>
			body {
				font-family: DejaVu Sans, sans-serif;
				font-size: 10px;
				/* Slightly smaller base font for more content per page */
				margin: 20px;
				color: #333;
				line-height: 1.6;
			}

			h2 {
				text-align: center;
				color: #1a202c;
				margin-bottom: 25px;
				font-size: 20px;
				padding-bottom: 10px;
				border-bottom: 2px solid #e2e8f0;
			}

			.header-info {
				text-align: right;
				font-size: 10px;
				margin-bottom: 20px;
				color: #666;
			}

			.section {
				margin-bottom: 25px;
				border: 1px solid #e2e8f0;
				border-radius: 6px;
				overflow: hidden;
				/* Ensures border-radius applies to children */
				page-break-inside: avoid;
				/* Keep sections together if possible */
			}

			.section-title {
				background-color: #f7fafc;
				color: #2d3748;
				font-weight: bold;
				padding: 10px 15px;
				border-bottom: 1px solid #e2e8f0;
				font-size: 14px;
			}

			.section-content {
				padding: 15px;
			}

			.grid-container {
				display: table;
				/* Use table-like display for consistent columns */
				width: 100%;
			}

			.grid-row {
				display: table-row;
			}

			.grid-cell {
				display: table-cell;
				padding: 8px 10px;
				vertical-align: top;
				width: 50%;
				/* Two columns */
			}

			.grid-cell strong {
				color: #4a5568;
				min-width: 100px;
				/* Align labels */
				display: inline-block;
			}

			table {
				width: 100%;
				border-collapse: collapse;
				margin-top: 10px;
				font-size: 10px;
			}

			table th,
			table td {
				border: 1px solid #e2e8f0;
				padding: 8px 10px;
				text-align: left;
				word-wrap: break-word;
				/* Prevent long text from overflowing */
			}

			table th {
				background-color: #ebf4ff;
				color: #2c5282;
				font-weight: bold;
				text-transform: uppercase;
			}

			table tbody tr:nth-child(even) {
				background-color: #f7fafc;
			}

			.info-box {
				border: 1px solid #cce5ff;
				/* Lighter blue border */
				background-color: #e6f3ff;
				/* Lighter blue background */
				padding: 10px;
				margin-bottom: 15px;
				border-radius: 5px;
			}

			.info-box p {
				margin: 0;
				padding: 0;
				margin-bottom: 5px;
			}

			.info-box p:last-child {
				margin-bottom: 0;
			}

			.info-box strong {
				color: #004085;
			}

			.signature,
			.image-preview {
				max-width: 150px;
				/* Slightly smaller for better layout */
				height: auto;
				margin-top: 10px;
				margin-right: 10px;
				border: 1px solid #e2e8f0;
				border-radius: 4px;
				padding: 3px;
			}

			.images-container {
				margin-top: 10px;
			}
		</style>
	</head>

	<body>
		<div class="header-info"> {{ __('locale.lbl_created_at') }}: {{ format_datetime(now(), 'short', 'short') }} </div>
		<h2>{{ __('locale.visite_prev.preventive_visit_report') }} #{{ $vp_visit->id }}</h2> {{-- Step 1: Basic Info --}} <div class="section">
			<div class="section-title">{{ __('locale.lbl_basic_info') }}</div>
			<div class="section-content">
				<div class="grid-container">
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_client') }}:</strong> {{ $vp_visit->client->raison_sociale ?? __('locale.not_available') }}</div>
						<div class="grid-cell"><strong>{{ __('locale.lbl_installer') }}:</strong> {{ $vp_visit->installer->name ?? __('locale.not_available') }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_created_at') }}:</strong> {{ format_datetime($vp_visit->created_at, 'short', 'short') }}</div>
						<div class="grid-cell">
							@if ($vp_visit->status)
								<strong>{{ __('visite_prev.status') }}:</strong> {{ $vp_visit->status->translated_name }}
							@endif
						</div>
					</div>
				</div>
			</div>
		</div> {{-- Step 2: Checklist --}} <div class="section">
			<div class="section-title">{{ __('locale.lbl_checklist') }}</div>
			<div class="section-content">
				<table>
					<thead>
						<tr>
							<th>#</th>
							<th>{{ __('locale.actions') }}</th>
							<th>{{ __('locale.menu_done') }}</th>
							<th>{{ __('locale.visite_prev.comments') }}</th>
							<th>{{ __('locale.visite_prev.todo') }}</th>
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
		</div> {{-- Step 3: Telecom Equipments --}} <div class="section">
			<div class="section-title">{{ __('locale.lbl_telecom_equipments') }}</div>
			<div class="section-content">
				@forelse($vp_visit->telecomEquipments as $equip)
					<div class="info-box">
						<p><strong>{{ __('locale.lbl_type') }}:</strong> {{ $equip->type }}</p>
						<p><strong>{{ __('locale.visite_prev.make') }}:</strong> {{ $equip->make }}</p>
						<p><strong>{{ __('locale.visite_prev.model') }}:</strong> {{ $equip->model }}</p>
						<p><strong>{{ __('locale.lbl_quantity') }}:</strong> {{ $equip->qty }}</p>
						<p><strong>{{ __('locale.visite_prev.place') }}:</strong> {{ $equip->place }}</p>
						@if ($equip->images)
							<div class="images-container">
								@foreach ($equip->images as $img)
									<img
										src="{{ str_starts_with($img, 'http') ? $img : public_path('storage/' . $img) }}" class="image-preview" alt="Equipment Image">
								@endforeach
							</div>
						@endif
				</div> @empty <p>{{ __('locale.no_telecom_equipment_found') }}</p>
				@endforelse
			</div>
		</div> {{-- Step 4: Patient Call Equipments --}} <div class="section">
			<div class="section-title">{{ __('locale.lbl_patient_call_equipments') }}</div>
			<div class="section-content">
				@forelse($vp_visit->patientCallEquipments as $equip)
					<div class="info-box">
						<p><strong>{{ __('locale.lbl_type') }}:</strong> {{ $equip->type }}</p>
						<p><strong>{{ __('locale.visite_prev.make') }}:</strong> {{ $equip->make }}</p>
						<p><strong>{{ __('locale.visite_prev.model') }}:</strong> {{ $equip->model }}</p>
						<p><strong>{{ __('locale.lbl_quantity') }}:</strong> {{ $equip->qty }}</p>
						<p><strong>{{ __('locale.visite_prev.place') }}:</strong> {{ $equip->place }}</p>
						@if ($equip->images)
							<div class="images-container">
								@foreach ($equip->images as $img)
									<img
										src="{{ str_starts_with($img, 'http') ? $img : public_path('storage/' . $img) }}" class="image-preview" alt="Equipment Image">
								@endforeach
							</div>
						@endif
				</div> @empty <p>{{ __('locale.no_patient_call_equipment_found') }}</p>
				@endforelse
			</div>
		</div>		{{-- Step 5: Interrupt Repairs --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_5_title') }}</div>
			<div class="section-content">
				<table>
					<thead>
						<tr>
							<th>{{ __('visite_prev.equipment') }}</th>
							<th>{{ __('visite_prev.disjoncteur') }}</th>
							<th>{{ __('visite_prev.type') }}</th>
							<th>{{ __('visite_prev.situation') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($vp_visit->repairInterrupts as $repair)
							<tr>
								<td>{{ $repair->equipment }}</td>
								<td>{{ $repair->disjoncteur ? __('locale.yes') : __('locale.no') }}</td>
								<td>{{ $repair->type }}</td>
								<td>{{ $repair->situation }}</td>
						</tr> @empty <tr>
								<td colspan="4">{{ __('locale.no_interrupt_repairs_found') }}</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		{{-- Step 6: Elevator Repairs --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_6_title') }}</div>
			<div class="section-content">
				@forelse($vp_visit->repairElevators as $elevator)
					<p><strong>{{ $elevator->elevator }}</strong> ({{ __('visite_prev.linenumber') }}:
					{{ $elevator->linenumber }}) — {{ __('visite_prev.passes_by_pabx') }}: {{ $elevator->passes_by_pabx ? __('locale.yes') : __('locale.no') }}</p> @empty <p>
						{{ __('locale.no_elevators_found') }}</p>
				@endforelse
			</div>
		</div>

		{{-- Step 7: Help Line Repairs --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_7_title') }}</div>
			<div class="section-content">
				@forelse($vp_visit->repairHelpLines as $line)
					<p><strong>{{ $line->name }}</strong> ({{ __('visite_prev.linenumber') }}: {{ $line->linenumber }}) — {{ __('visite_prev.passes_by_pabx') }}:
					{{ $line->passes_by_pabx ? __('locale.yes') : __('locale.no') }}</p> @empty <p>{{ __('locale.no_help_lines_found') }}</p>
				@endforelse
			</div>
		</div>

		{{-- Step 8: Input Devices --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_8_title') }}</div>
			<div class="section-content">
				<table>
					<thead>
						<tr>
							<th>{{ __('visite_prev.name') }}</th>
							<th>{{ __('visite_prev.model') }}</th>
							<th>{{ __('visite_prev.batteries') }}</th>
							<th>{{ __('visite_prev.voltage') }}</th>
							<th>{{ __('visite_prev.todo') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($vp_visit->inputs as $input)
							<tr>
								<td>{{ $input->name }}</td>
								<td>{{ $input->model }}</td>
								<td>{{ $input->number_of_batteries }}</td>
								<td> {{ __('visite_prev.charge_tension') }}: {{ $input->charge_tension }}V<br> {{ __('visite_prev.battery_tension') }}:
									{{ $input->battery_tension }}V<br> {{ __('visite_prev.tension_after_power_cut') }}: {{ $input->tension_after_power_cut }}V<br>
									{{ __('visite_prev.tension_after_30mins_power_cut') }}: {{ $input->tension_after_30mins_power_cut }}V </td>
								<td> {{ __('visite_prev.needs_change') }}: {{ $input->needs_change ? __('locale.yes') : __('locale.no') }}<br>
									{{ __('visite_prev.battery_needs_change') }}: {{ $input->battery_needs_change ? __('locale.yes') : __('locale.no') }} </td>
						</tr> @empty <tr>
								<td colspan="5">{{ __('locale.no_input_devices_found') }}</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		{{-- Step 9: Technician Training --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_9_title') }}</div>
			<div class="section-content">
				<p><strong>{{ __('visite_prev.common_points_discussed') }}:</strong> {!! $vp_visit->common_points_discussed !!}</p>
				@forelse($vp_visit->trainingTechs as $tech)
					<div class="info-box">
						<p><strong>{{ __('visite_prev.name') }}:</strong> {{ $tech->first_name }} {{ $tech->last_name }}</p>
						<p><strong>{{ __('visite_prev.position') }}:</strong> {{ $tech->position }}</p>
						@if ($tech->signature)
							<div>
								<img src="{{ public_path($tech->signature) }}" class="signature" alt="Technician Signature">
							</div>
						@endif
				</div> @empty <p>{{ __('locale.no_technician_training_found') }}</p>
				@endforelse
			</div>
		</div>

		{{-- Step 10: Admin Training --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_10_title') }}</div>
			<div class="section-content">
				<p><strong>{{ __('visite_prev.common_points_discussed_admin') }}:</strong> {!! $vp_visit->common_points_discussed_admin !!}</p>
				@forelse($vp_visit->trainingAdmins as $admin)
					<div class="info-box">
						<p><strong>{{ __('visite_prev.name') }}:</strong> {{ $admin->first_name }} {{ $admin->last_name }}</p>
						<p><strong>{{ __('visite_prev.position') }}:</strong> {{ $admin->position }}</p>
						@if ($admin->signature)
							<div>
								<img src="{{ public_path($admin->signature) }}" class="signature" alt="Admin Signature">
							</div>
						@endif
				</div> @empty <p>{{ __('locale.no_admin_training_found') }}</p>
				@endforelse
			</div>
		</div>

		{{-- Step 11: Nurses Training --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_11_title') }}</div>
			<div class="section-content">
				<p><strong>{{ __('visite_prev.common_points_discussed_nurse') }}:</strong> {!! $vp_visit->common_points_discussed_nurse !!}</p>
				@forelse($vp_visit->trainingNurses as $nurse)
					<div class="info-box">
						<p><strong>{{ __('visite_prev.name') }}:</strong> {{ $nurse->first_name }} {{ $nurse->last_name }}</p>
						<p><strong>{{ __('visite_prev.position') }}:</strong> {{ $nurse->position }}</p>
						@if ($nurse->signature)
							<div>
								<img src="{{ public_path($nurse->signature) }}" class="signature" alt="Nurse Signature">
							</div>
						@endif
				</div> @empty <p>{{ __('locale.no_nurse_training_found') }}</p>
				@endforelse
			</div>
		</div>

		{{-- Step 12: DECT Controls --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_12_title') }}</div>
			<div class="section-content">
				<table>
					<thead>
						<tr>
							<th>{{ __('visite_prev.linenumber') }}</th>
							<th>{{ __('visite_prev.reference') }}</th>
							<th>{{ __('locale.lbl_status') }}</th>
							<th>{{ __('visite_prev.todo') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($vp_visit->dectControls as $dect)
							<tr>
								<td>{{ $dect->numero }}</td>
								<td>{{ $dect->reference }}</td>
								<td> 
                                    {{ __('visite_prev.verified') }}: {{ $dect->verified ? '✔' : '✘' }}
                                    @if(!$dect->verified)
                                        <br><strong>{{ __('visite_prev.reason') }}:</strong> {{ $dect->reason }}
                                    @endif
                                    <br>{{ __('visite_prev.cover_hs') }}: {{ $dect->cover_hs ? 'X' : '-' }}, {{ __('visite_prev.screen_hs') }}: {{ $dect->screen_hs ? 'X' : '-' }},
									{{ __('visite_prev.battery_hs') }}: {{ $dect->battery_hs ? 'X' : '-' }}, {{ __('visite_prev.mic_hs') }}: {{ $dect->mic_hs ? 'X' : '-' }},
									{{ __('visite_prev.speaker_hs') }}: {{ $dect->speaker_hs ? 'X' : '-' }} 
                                </td>
								<td>{{ $dect->todo }}</td>
						</tr> @empty <tr>
								<td colspan="4">{{ __('locale.no_dect_controls_found') }}</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		{{-- Step 13: Config Backups --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_13_title') }}</div>
			<div class="section-content">
				<table>
					<thead>
						<tr>
							<th>{{ __('visite_prev.name') }}</th>
							<th>{{ __('visite_prev.file') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($vp_visit->configBackups as $backup)
							<tr>
								<td>{{ $backup->name }}</td>
								<td>{{ $backup->filename }}</td>
						</tr> @empty <tr>
								<td colspan="2">{{ __('visite_prev.no_config_backups_found') }}</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		{{-- Step 14: Stock --}}
		<div class="section">
			<div class="section-title">{{ __('visite_prev.step_14_title') }}</div>
			<div class="section-content">
				<table>
					<thead>
						<tr>
							<th>{{ __('visite_prev.category') }}</th>
							<th>{{ __('visite_prev.type') }}</th>
							<th>{{ __('visite_prev.make') }}</th>
							<th>{{ __('visite_prev.model') }}</th>
							<th>{{ __('visite_prev.qty') }}</th>
							<th>{{ __('visite_prev.references') }}</th>
						</tr>
					</thead>
					<tbody>
						@forelse($vp_visit->stocks as $stock)
							<tr>
								<td>{{ $stock->category }}</td>
								<td>{{ $stock->type }}</td>
								<td>{{ $stock->make }}</td>
								<td>{{ $stock->model }}</td>
								<td>{{ $stock->qty }}</td>
								<td>{{ $stock->references }}</td>
						</tr> @empty <tr>
								<td colspan="6">{{ __('locale.no_stock_items_found') }}</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</body>

</html>
