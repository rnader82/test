<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>{{ __('locale.lbl_report_intervention') }}</title>
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
				min-width: 120px;
				/* Align labels */
				display: inline-block;
			}

			ul {
				list-style: none;
				padding-left: 0;
				margin: 0;
			}

			ul li {
				margin-bottom: 5px;
				padding-left: 15px;
				position: relative;
			}

			ul li:before {
				content: '•';
				/* Custom bullet point */
				color: #4299e1;
				/* A nice blue color */
				position: absolute;
				left: 0;
				top: 0;
			}

			.stage-item {
				margin-bottom: 10px;
				background-color: #fdfdfd;
				border-left: 3px solid #4299e1;
				padding: 8px 12px;
				border-radius: 4px;
			}

			.stage-item strong {
				color: #2c5282;
				font-size: 12px;
			}

			.stage-item small {
				display: block;
				color: #718096;
				margin-top: 2px;
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
		</style>
	</head>

	<body>
		<div class="header-info"> {{ __('locale.lbl_created_at') }}: {{ format_datetime(now(), 'short', 'short') }} </div>
		<h2>{{ __('locale.lbl_report_intervention') }} # {{ $technicalIntervention->id }}</h2>
		{{-- General + Client Info --}}
		<div class="section">
			<div class="section-title">{{ __('locale.lbl_general_info') }} & {{ __('locale.lbl_site_information') }}</div>
			<div class="section-content">
				<div class="grid-container">
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.subject') }}:</strong> {{ $technicalIntervention->subject }}</div>
						<div class="grid-cell"><strong>{{ __('locale.lbl_client') }}:</strong> {{ $technicalIntervention->client->raison_sociale }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_postal_code') }}:</strong> {{ $technicalIntervention->client->code_postal }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_priority') }}:</strong> {{ __('locale.criticity_' . strtolower($technicalIntervention->priority)) }}</div>
						<div class="grid-cell"><strong>{{ __('locale.division_zone') }}:</strong> {{ $technicalIntervention->divisionZone->name ?? __('locale.not_available') }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_criticity_internal') }}:</strong> {{ __('locale.criticity_' . strtolower($technicalIntervention->criticity_internal)) }}</div>
						<div class="grid-cell"><strong>{{ __('locale.lbl_planned') }}:</strong> {{ $technicalIntervention->is_planned ? __('locale.yes') : __('locale.no') }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_criticity_client') }}:</strong> {{ __('locale.criticity_' . strtolower($technicalIntervention->criticity_external)) }}</div>
						<div class="grid-cell"><strong>{{ __('locale.lbl_visible_to_client') }}:</strong> {{ $technicalIntervention->is_visible_to_client ? __('locale.yes') : __('locale.no') }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_intervention_date') }}:</strong> {{ format_datetime($technicalIntervention->intervention_date, 'short', 'short') }}</div>
						<div class="grid-cell"><strong>{{ __('locale.lbl_end_date') }}:</strong>
							{{ $technicalIntervention->end_date ? format_datetime($technicalIntervention->end_date, 'short', 'short') : __('locale.not_available') }}</div>
					</div>
					<div class="grid-row">
						<div class="grid-cell"><strong>{{ __('locale.lbl_estimated_duration') }}:</strong>
							@if (!is_null($technicalIntervention->estimated_duration))
								@php
									$days = intdiv($technicalIntervention->estimated_duration, 1440);
									$remainingMinutes = $technicalIntervention->estimated_duration % 1440;
									$hours = intdiv($remainingMinutes, 60);
									$minutes = $remainingMinutes % 60;
								@endphp
								{{ $days > 0 ? $days . 'd ' : '' }}{{ sprintf('%02d:%02d', $hours, $minutes) }}
							@else
								{{ __('locale.not_available') }}
							@endif
						</div>
						<div class="grid-cell"></div>
					</div>
				</div>
			</div>
		</div> {{-- Classification --}} <div class="section">
			<div class="section-title">{{ __('locale.lbl_classifiction') }}</div>
			<div class="section-content">
				<ul>
					<li><strong>{{ __('locale.lbl_severity') }}:</strong> {{ $technicalIntervention->severity->translated_name }}</li>
					<li><strong>{{ __('locale.lbl_technical_intervention_types') }}:</strong> {{ $technicalIntervention->type->translated_name }}</li>
					<li><strong>{{ __('locale.lbl_metier') }}:</strong> {{ $technicalIntervention->metiers->pluck('translated_name')->implode(', ') }}</li>
					<li><strong>{{ __('locale.status') }}:</strong> {{ $technicalIntervention->status->translated_name }}</li>
					<li><strong>{{ __('locale.lbl_state') }}:</strong> {{ $technicalIntervention->state->translated_name }}</li>
				</ul>
			</div>
		</div> {{-- Assigned Users --}} <div class="section">
			<div class="section-title">{{ __('locale.lbl_users') }}</div>
			<div class="section-content">
				<ul>
					@forelse($technicalIntervention->assignedUsers as $user)
						<li>{{ $user->name }}</li>
					@empty
						<li>{{ __('locale.not_available_users') }}</li>
					@endforelse
				</ul>
			</div>
		</div>
		{{-- Process Stages --}}
		@if ($technicalIntervention->processes->count())
			<div class="section">
				<div class="section-title">{{ __('locale.lbl_process_stages') }}</div>
				<div class="section-content">
					@foreach ($technicalIntervention->processes as $process)
						<div class="stage-item">
							<strong>{{ $process->translated_name }}</strong>
							<small>{!! $process->translated_description !!}</small>
						</div>
					@endforeach
				</div>
			</div>
		@endif
		{{-- Equipment Table --}}
		@if ($technicalIntervention->equipments->count())
			<div class="section">
				<div class="section-title">{{ __('locale.menu_equipments') }}</div>
				<div class="section-content">
					<table>
						<thead>
							<tr>
								<th>{{ __('locale.equipment_name') }}</th>
								<th>{{ __('locale.lbl_reference') }}</th>
								<th>{{ __('locale.lbl_quantity') }}</th>
								<th>{{ __('locale.lbl_localisation') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($technicalIntervention->equipments as $equipment)
								<tr>
									<td>{{ $equipment->name }}</td>
									<td>{{ $equipment->reference }}</td>
									<td>{{ $equipment->pivot->quantity }}</td>
									<td>{{ $equipment->pivot->localisation }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		@endif
	</body>

</html>
