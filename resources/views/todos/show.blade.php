@extends('layouts.app')
@section('portal-module', 'todos')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'dashboard')
@section('portal-icon', 'house')


@section('title', __('locale.todo.details_title')) 

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.todo.details_title') }}</h1>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') }}
    </a>
@endsection

@section('content')
<style>
	.border-start-md {
		border-left: 1px solid rgba(67, 89, 113, 0.08);
	}
</style>
@php
    $byLabel = __('locale.todo.lbl_by');
@endphp

<div class="content-wrapper">
	<div class="container-xxl flex-grow-1">
		<div class="row">
			<!-- Main Todo Details Card -->
			<div class="col-md-7 mb-4">
				<div class="module-record-card card card-premium">
					<div class="card-body p-4">
						<div class="d-flex align-items-center justify-content-between mb-4">
							<h5 class="fw-bold mb-0 text-primary">{{ $todo->title }}</h5>
							<span class="badge bg-{{ $todo->status === 'done' ? 'success' : ($todo->status === 'in_progress' ? 'warning' : ($todo->status === 'pending_other' ? 'danger' : 'secondary')) }}">
								{{ __('locale.todo.status.' . $todo->status) }}
							</span>
						</div>
						
						<h6 class="fw-semibold text-muted mb-2">{{ __('locale.todo.lbl_description') }}</h6>
						<p class="text-dark bg-light p-3 rounded border mb-4" style="white-space: pre-line; min-height: 100px;">{{ $todo->description ?: __('locale.no_description') }}</p>
						
						<div class="row">
							<div class="col-sm-6 mb-3 mb-sm-0">
								<h6 class="fw-semibold text-muted mb-2">{{ __('locale.todo.lbl_users') }}</h6>
								<div class="d-flex flex-wrap gap-1">
									@forelse ($todo->users as $user)
										<span class="badge bg-label-info px-3 py-2 fw-semibold" title="{{ $user->name }}">{{ $user->name }}</span>
									@empty
										<span class="text-muted small italic">{{ __('locale.lbl_no_users_assigned') }}</span>
									@endforelse
								</div>
							</div>
							
							<div class="col-sm-6">
								<h6 class="fw-semibold text-muted mb-2">{{ __('locale.todo.lbl_duration') }}</h6>
								@if ($todo->formatted_duration)
									<p class="mb-0 text-primary fw-bold"><i class="bi bi-clock me-1"></i> {{ $todo->formatted_duration }}</p>
								@else
									<p class="mb-0 text-muted italic"><i class="bi bi-clock me-1"></i> {{ __('locale.todo.not_completed') }}</p>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Status Updates Timeline Card -->
			<div class="col-md-5 mb-4">
				<div class="module-record-card card card-premium">
					<div class="card-body p-4">
						<h5 class="fw-bold mb-4">{{ __('locale.todo.lbl_status_updates') }}</h5>
						
						<ul class="list-unstyled mb-0"> 
							@php $last_status = 'todo'; @endphp 
							@forelse ($todo->statuses as $status)
								<li class="mb-3 d-flex align-items-start gap-2 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
									<span class="badge bg-{{ $status->status === 'done' ? 'success' : ($status->status === 'in_progress' ? 'warning' : ($status->status === 'pending_other' ? 'danger' : 'secondary')) }} mt-1" style="font-size: 0.75rem;">
										{{ __('locale.todo.status.' . $status->status) }}
									</span>
									<div class="flex-grow-1">
										<div class="small text-muted">
											{{ format_datetime($status->created_at, 'short', 'short') }} {{ $byLabel }} <span class="fw-semibold">{{ $status->user->name }}</span>
										</div>
										@if($status->comment)
											<div class="small text-dark mt-1 bg-light p-2 rounded border">{{ $status->comment }}</div>
										@else
											<div class="small text-muted italic mt-1">{{ __('locale.todo.no_comment') }}</div>
										@endif
									</div>
								</li> 
								@php $last_status = $status->status; @endphp
							@empty
								<li class="text-muted small">{{ __('locale.no_records_found') }}</li>
							@endforelse
						</ul>
						
						@if ($last_status !== 'done')
							<form action="{{ route('todos.updateStatus', $todo) }}" method="POST" class="row g-2 align-items-end mt-3 pt-3 border-top"> 
								@csrf 
								<div class="col-12 mb-2">
									<label for="status-{{ $todo->id }}" class="form-label fw-semibold small mb-1">{{ __('locale.change_status') }}</label>
									<select name="status" id="status-{{ $todo->id }}" class="form-select form-select-sm">
										<option value="todo" {{ $todo->status === 'todo' ? 'selected' : '' }}>{{ __('locale.todo.status.todo') }}</option>
										<option value="in_progress" {{ $todo->status === 'in_progress' ? 'selected' : '' }}>{{ __('locale.todo.status.in_progress') }}</option>
										<option value="pending_other" {{ $todo->status === 'pending_other' ? 'selected' : '' }}>{{ __('locale.todo.status.pending_other') }}</option>
										<option value="done" {{ $todo->status === 'done' ? 'selected' : '' }}>{{ __('locale.todo.status.done') }}</option>
									</select>
								</div>
								<div class="col-12 mb-3">
									<label for="comment-{{ $todo->id }}" class="form-label fw-semibold small mb-1">{{ __('locale.lbl_commentaire') }} <span class="text-muted fw-normal">({{ __('locale.lbl_optional') }})</span></label>
									<textarea name="comment" id="comment-{{ $todo->id }}" class="form-control form-control-sm" rows="2"></textarea>
								</div>
								<div class="col-12">
									<button type="submit" class="btn btn-sm btn-primary w-100">{{ __('locale.update') }}</button>
								</div>
							</form>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

