@extends('layouts.app')
@section('portal-module', 'users')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_users'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ $showDeleted ? __('locale.lbl_deleted_users') : __('locale.lbl_users') }}</h1>
        <span class="header-count">({{ $users->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('users.index') }}" class="w-100">
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
    <a href="{{ route('users.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_user') ?? (isset($user) ? __('locale.add_user') : 'Nouveau') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_user') ?? 'Nouvel utilisateur' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

		{{-- Toggle: Show deleted users --}}
		<div class="mb-3 d-flex align-items-center gap-3">
			@if($showDeleted)
				<a href="{{ route('users.index', array_merge(request()->except(['show_deleted', 'page']), [])) }}"
				   class="btn btn-sm btn-outline-secondary">
					<i class="bi bi-people me-1"></i>{{ __('locale.lbl_users') }}
				</a>
				<span class="badge bg-danger">
					<i class="bi bi-trash me-1"></i>{{ __('locale.lbl_deleted_users') }}
				</span>
			@else
				<a href="{{ route('users.index', array_merge(request()->except(['page']), ['show_deleted' => 1])) }}"
				   class="btn btn-sm btn-outline-danger">
					<i class="bi bi-trash me-1"></i>{{ __('locale.lbl_show_deleted_users') }}
				</a>
			@endif
		</div>

		<table class="module-table table table-bordered">
			<thead>
				<tr>
					<th>{{ __('locale.name') }}</th>
					<th>{{ __('locale.lbl_email') }}</th>
					<th>{{ __('locale.employee_or_admin') }}</th>
					@if($showDeleted)
						<th>{{ __('locale.lbl_deleted_at') }}</th>
					@endif
					{{-- <th>Roles</th> --}}
					<th>{{ __('locale.actions') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $user)
					<tr @if($showDeleted) class="table-danger" @endif style="cursor: pointer;" onclick="window.location='{{ route('users.show', $user) }}'">
						<td>
							<a href="{{ route('users.show', $user) }}" class="fw-semibold text-dark text-decoration-none">
								{{ $user->name }}
							</a>
							@if($showDeleted)
								<span class="badge bg-danger ms-1">
									<i class="bi bi-trash me-1"></i>{{ __('locale.badge_deleted') }}
								</span>
							@endif
						</td>
						<td>{{ $user->email }}</td>
						<td>
							@if ($user->is_admin)
								{{ __('locale.admin') }}
							@endif
							@if ($user->is_employee)
								{{ __('locale.employee') }}
							@endif
						</td>
						@if($showDeleted)
							<td>{{ $user->deleted_at ? $user->deleted_at->format('d/m/Y H:i') : '—' }}</td>
						@endif
						{{-- <td>
                            @foreach ($user->roles as $role)
                                <span class="badge bg-secondary">{{ $role->name }}</span>
                            @endforeach
                        </td> --}}
						<td onclick="event.stopPropagation();">
							@if(!$showDeleted)
								@if ($user->id !== auth()->id())
									<div class="dropdown">
										<button class="btn btn-sm btn-outline-secondary" type="button" id="userActionMenu{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="bi bi-three-dots-vertical"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userActionMenu{{ $user->id }}">
											<li>
												<a href="{{ route('users.show', $user) }}" class="dropdown-item">
													<i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
												</a>
											</li>
											<li>
												<a href="{{ route('users.edit', $user) }}" class="dropdown-item">
													<i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
												</a>
											</li>
											<li>
												<form action="{{ route('users.destroy', $user) }}" method="POST">
													@csrf
													@method('DELETE')
													<button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.dashboard_new.confirm_delete_user') }}')">
														<i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
													</button>
												</form>
											</li>
										</ul>
									</div>
								@else
									<div class="dropdown">
										<button class="btn btn-sm btn-outline-secondary" type="button" id="userActionMenu{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
											<i class="bi bi-three-dots-vertical"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userActionMenu{{ $user->id }}">
											<li>
												<a href="{{ route('users.show', $user) }}" class="dropdown-item">
													<i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
												</a>
											</li>
											<li>
												<a href="{{ route('users.edit', $user) }}" class="dropdown-item">
													<i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
												</a>
											</li>
										</ul>
									</div>
								@endif
							@else
								<span class="text-muted">—</span>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<div class="mt-4">
			{{ $users->withQueryString()->links() }}
		</div>
	</div>
</div>

@if(!$showDeleted)
@endif

@endsection
