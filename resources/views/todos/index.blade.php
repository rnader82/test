@extends('layouts.app')
@section('portal-module', 'todos')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'dashboard')
@section('portal-icon', 'house')


@section('title', __('locale.todo.list_title')) 

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0">{{ __('locale.todo.list_title') }}</h1>
        <span class="header-count">({{ method_exists($todos, 'total') ? $todos->total() : $todos->count() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('todos.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <a href="{{ route('todos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_todo') }}
    </a>
@endsection

@section('content') 
<style>
	.todo-title-button {
		text-decoration: none;
		color: inherit;
		cursor: pointer;
	}

	.todo-title-button:hover {
		color: var(--bs-primary);
		text-decoration: underline;
	}
	
	.border-start-md {
		border-left: 1px solid rgba(67, 89, 113, 0.08);
	}
</style>
@php
    $statusLabel = __('locale.status');
    if ($statusLabel === 'locale.status') {
        $statusLabel = __('locale.lbl_status');
    }
    $byLabel = __('locale.todo.lbl_by');
@endphp

<div class="content-wrapper">
	<div class="container-xxl flex-grow-1">
		<div class="card card-premium">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="module-table table table-bordered table-hover align-middle mb-0" id="todoTable">
						<thead>
							<tr>
								<th style="width: 80px;">#</th>
								<th>{{ __('locale.todo.lbl_title') }}</th>
								<th>{{ __('locale.todo.lbl_description') }}</th>
								<th>{{ __('locale.todo.lbl_users') }}</th>
								<th class="text-center" style="width: 150px;">{{ $statusLabel }}</th>
								<th class="text-center" style="width: 100px;">{{ __('locale.actions') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($todos as $index => $todo)
								<tr data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="false" aria-controls="collapse-{{ $index }}"
									class="accordion-toggle" style="cursor: pointer;">
									<td>{{ $todo->id }}</td>
									<td>
										<span class="todo-title-button fw-semibold">{{ $todo->title }}</span>
									</td>
									<td>{{ \Illuminate\Support\Str::limit($todo->description, 60) }}</td>
									<td>
										@if ($todo->users->isNotEmpty())
											@foreach ($todo->users as $user)
												<span class="badge" style="background-color: rgba(38, 198, 249, 0.15); color: #0288d1;" title="{{ $user->name }}">{{ $user->identifier }}</span>
											@endforeach
										@else
											<span class="text-muted small">{{ __('locale.lbl_no_users_assigned') }}</span>
										@endif
									</td>
									<td class="text-center">
										<span class="badge bg-{{ $todo->status === 'done' ? 'success' : ($todo->status === 'in_progress' ? 'warning' : ($todo->status === 'pending_other' ? 'danger' : 'secondary')) }}">
											{{ __('locale.todo.status.' . $todo->status) }}
										</span>
									</td>
									<td class="text-center">
										<div class="dropdown" onclick="event.stopPropagation();">
											<button class="btn btn-sm btn-outline-secondary" type="button" id="todoActionMenu{{ $todo->id }}" data-bs-toggle="dropdown" aria-expanded="false">
												<i class="bi bi-three-dots-vertical"></i>
											</button>
											<ul class="dropdown-menu" aria-labelledby="todoActionMenu{{ $todo->id }}">
												<li>
													<a href="{{ route('todos.edit', $todo) }}" class="dropdown-item" onclick="event.stopPropagation();">
														<i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') }} </a>
												</li>
												<li>
													<form action="{{ route('todos.destroy', $todo) }}" method="POST" onclick="event.stopPropagation();"> @csrf @method('DELETE') <button type="submit"
															class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}');">
															<i class="bi bi-trash me-1"></i>{{ __('locale.delete') }} </button>
													</form>
												</li>
											</ul>
										</div>
									</td>
								</tr>
								<tr class="collapse" id="collapse-{{ $index }}" data-bs-parent="#todoTable">
									<td colspan="6" class="p-0">
										<div class="p-4 border-top border-bottom bg-light" style="background-color: #fafbfc !important;">
											<div class="row">
												<div class="col-md-7 mb-3 mb-md-0">
													<h6 class="fw-bold mb-2">{{ __('locale.todo.lbl_description') }}</h6>
													<p class="text-muted mb-4" style="white-space: pre-line;">{{ $todo->description ?: __('locale.no_description') }}</p>
													
													@if ($todo->formatted_duration)
														<p class="mb-0"><i class="bi bi-clock me-1 text-primary"></i> <strong>{{ __('locale.todo.lbl_duration') }}:</strong> <span class="badge bg-label-primary fw-semibold">{{ $todo->formatted_duration }}</span></p>
													@else
														<p class="mb-0"><i class="bi bi-clock me-1 text-muted"></i> <strong>{{ __('locale.todo.lbl_duration') }}:</strong> <span class="badge bg-light text-muted fw-normal">{{ __('locale.todo.not_completed') }}</span></p>
													@endif
												</div>
												<div class="col-md-5 border-start-md">
													<h6 class="fw-bold mb-3">{{ __('locale.todo.lbl_status_updates') }}</h6>
													<ul class="list-unstyled mb-4"> 
														@php $last_status = 'todo'; @endphp 
														@forelse ($todo->statuses as $status)
															<li class="mb-3 d-flex align-items-start gap-2">
																<span class="badge bg-{{ $status->status === 'done' ? 'success' : ($status->status === 'in_progress' ? 'warning' : ($status->status === 'pending_other' ? 'danger' : 'secondary')) }} mt-1" style="font-size: 0.75rem;">
																	{{ __('locale.todo.status.' . $status->status) }}
																</span>
																<div class="flex-grow-1">
																	<div class="small text-muted">
																		{{ format_datetime($status->created_at, 'short', 'short') }} {{ $byLabel }} <span class="fw-semibold">{{ $status->user->name }}</span>
																	</div>
																	@if($status->comment)
																		<div class="small text-dark mt-1 bg-white p-2 rounded border">{{ $status->comment }}</div>
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
														<form action="{{ route('todos.updateStatus', $todo) }}" method="POST" class="row g-2 align-items-end mt-2 pt-3 border-top"> 
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
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@if(method_exists($todos, 'links'))
					<div class="p-3 border-top d-flex justify-content-end">
						{{ $todos->withQueryString()->links() }}
					</div>
				@endif
			</div>
		</div>
	</div>
</div>

@endsection


