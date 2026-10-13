@extends('layouts.app')

@section('content')
	<div class="container text-center">
		<h2 class="mb-4">{{ __('locale.visite_prev.visit_complete_title') }}</h2>

		@if (session('success'))
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		@endif

		<p class="lead">{{ __('locale.visite_prev.visit_complete_summary') }}</p>

		@if ($vp_visit->status)
			<div class="mb-4">
				<span class="badge fs-5" style="background-color: {{ $vp_visit->status->color }}; color: {{ $vp_visit->status->text_color }};">
					{{ __('visite_prev.status') }}: {{ $vp_visit->status->translated_name }}
				</span>
			</div>
		@endif

		<a target="_blank" href="{{ route('vp-visit-workflow.preview', $vp_visit->id) }}" class="btn btn-outline-secondary mt-2">
			{{ __('locale.visite_prev.preview_pdf_html') }}
		</a>
		<br /><br />
		<a href="{{ route('vp-visit-workflow.edit', $vp_visit->id) }}" class="btn btn-outline-primary mt-3">
			{{ __('locale.visite_prev.edit_vp_visit') }}
		</a>
		<br /><br />
		<a href="{{ route('vp-visit-workflow.export', $vp_visit->id) }}" class="btn btn-outline-primary mt-3">
			{{ __('locale.visite_prev.download_pdf') }}
		</a>
	</div>
@endsection
