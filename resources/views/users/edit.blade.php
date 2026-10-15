@extends('layouts.app')
@section('portal-module', 'users')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_edit_user'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_user') }}: {{ $user->name }}</h1>
    </div>
@endsection


@section('content')
	<div class="container">
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form action="{{ route('users.update', $user->id) }}" method="POST">
			@csrf
			@method('PUT')

			<div class="row">
				<!-- User Information Card -->
				<div class="col-md-12 mb-4">
					<div class="module-form-card card">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="mb-0">{{ __('locale.lbl_user_info') }}</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="first_name" class="form-label">{{ __('locale.prenom') }}</label>
									<input type="text" name="first_name" id="first_name" class="form-control" value="{{ $user->first_name }}">
								</div>
								<div class="col-md-6 mb-3">
									<label for="last_name" class="form-label">{{ __('locale.nom') }}</label>
									<input type="text" name="last_name" id="last_name" class="form-control" value="{{ $user->last_name }}">
								</div>
								<div class="col-md-6 mb-3">
									<label for="name" class="form-label">{{ __('locale.name') }}</label>
									<input type="text" name="name" id="name" class="form-control" required value="{{ $user->name }}">
								</div>
								<div class="col-md-6 mb-3">
									<label for="email" class="form-label">{{ __('locale.lbl_email') }}</label>
									<input type="email" class="form-control" disabled value="{{ $user->email }}">
								</div>
								<div class="col-md-6 mb-3">
									<label for="password" class="form-label">{{ __('locale.lbl_password') }}</label>
									<input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
								</div>
								<div class="col-md-6 mb-3">
									<label for="password_confirmation" class="form-label">{{ __('locale.lbl_confirm_password') }}</label>
									<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
								</div>
								<div class="col-md-4 mb-3">
									<label for="color" class="form-label">{{ __('locale.lbl_color') }}</label>
									<input type="color" name="color" id="color" class="form-control form-control-color w-100" value="{{ $user->color ?? '#008cd2' }}" title="{{ __('locale.lbl_choose_user_color') }}">
								</div>
								<div class="col-md-8 mb-3 d-flex align-items-end gap-4">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="is_employee" id="is_employee" {{ $user->is_employee ? 'checked' : '' }}>
										<label class="form-check-label" for="is_employee">{{ __('locale.lbl_is_employee') }}</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" {{ $user->is_admin ? 'checked' : '' }}>
										<label class="form-check-label" for="is_admin">{{ __('locale.lbl_is_admin') }}</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="is_client_admin" id="is_client_admin" {{ $user->is_client_admin ? 'checked' : '' }}>
										<label class="form-check-label" for="is_client_admin">{{ __('locale.lbl_is_client_admin') }}</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Tabs Section -->
				<div class="col-md-12">
					<div class="nav-tabs-custom mb-4">
						<ul class="nav nav-tabs" id="userTabs" role="tablist">
							@if (auth()->user()->isNovalyoAdmin())
								<li class="nav-item" role="presentation">
									<button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">{{ __('locale.lbl_company_access') }}</button>
								</li>
							@endif
							<li class="nav-item" role="presentation">
								<button class="nav-link {{ !auth()->user()->isNovalyoAdmin() ? 'active' : '' }}" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">{{ __('locale.lbl_roles') }}</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">{{ __('locale.menu_permissions') }}</button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab">{{ __('locale.lbl_user_assignments') }}</button>
							</li>
							<li class="nav-item" role="presentation" id="document-types-nav-item" style="display: none;">
								<button class="nav-link" id="document-types-tab" data-bs-toggle="tab" data-bs-target="#document-types" type="button" role="tab">
									<i class="bi bi-file-earmark-lock me-1"></i> {{ __('locale.document_types_assignment') }}
									<span class="badge bg-primary rounded-pill ms-1" id="document-types-count">{{ count(old('client_document_types', $assignedDocumentTypeIds ?? [])) }}</span>
								</button>
							</li>
						</ul>
						<div class="tab-content bg-white p-4 border border-top-0 rounded-bottom" id="userTabsContent">
							<!-- Company Access Tab -->
							@if (auth()->user()->isNovalyoAdmin())
								<div class="tab-pane fade show active" id="company" role="tabpanel">
									@php
										$companyAccess = old('company_access', $user->getAllowedCompanyPrefixes());
										$customCompanyAccess = old('custom_company_access');
										if ($customCompanyAccess === null) {
											$customCompanyAccess = implode(', ', array_diff($companyAccess, ['NO', 'NS']));
										}
									@endphp
									<div class="row">
										<div class="col-md-6">
											<div class="form-check mb-2">
												<input class="form-check-input" type="checkbox" name="company_access[]" id="company_access_no" value="NO"
													{{ in_array('NO', $companyAccess, true) ? 'checked' : '' }}>
												<label class="form-check-label" for="company_access_no">{{ __('locale.lbl_company_novafr') }}</label>
											</div>
											<div class="form-check mb-2">
												<input class="form-check-input" type="checkbox" name="company_access[]" id="company_access_ns" value="NS"
													{{ in_array('NS', $companyAccess, true) ? 'checked' : '' }}>
												<label class="form-check-label" for="company_access_ns">{{ __('locale.lbl_company_novaso') }}</label>
											</div>
										</div>
										<div class="col-md-6">
											<label class="form-label" for="custom_company_access">{{ __('locale.lbl_custom_company_access') }}</label>
											<input type="text" name="custom_company_access" id="custom_company_access" class="form-control"
												value="{{ $customCompanyAccess }}" placeholder="XY, AB1">
											<small class="form-text text-muted">{{ __('locale.lbl_custom_company_access_help') }}</small>
										</div>
									</div>
								</div>
							@endif

							<!-- Roles Tab -->
							<div class="tab-pane fade {{ !auth()->user()->isNovalyoAdmin() ? 'show active' : '' }}" id="roles" role="tabpanel">
								<div class="row">
									@foreach ($roles as $role)
										<div class="col-md-3 mb-2">
											<div class="form-check card p-3 h-100 shadow-sm border-0 bg-light">
												<input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
													{{ $user->roles->contains($role->id) ? 'checked' : '' }}>
												<label class="form-check-label fw-bold" for="role_{{ $role->id }}">
													{{ $role->name }}
												</label>
												<small class="text-muted d-block mt-1">{{ $role->description }}</small>
											</div>
										</div>
									@endforeach
								</div>
							</div>

							<!-- Permissions Tab -->
							<div class="tab-pane fade" id="permissions" role="tabpanel">
								<div class="row mb-3 align-items-center">
									<div class="col-md-6">
										<div class="form-check">
											<input type="checkbox" id="selectAllPermissions" class="form-check-input">
											<label class="form-check-label fw-bold" for="selectAllPermissions">{{ __('locale.lbl_select_all_permissions') }}</label>
										</div>
									</div>
									<div class="col-md-6 text-end">
										<div class="input-group input-group-sm">
											<span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
											<input type="text" id="permissionSearch" class="form-control border-start-0" placeholder="Search permissions...">
										</div>
									</div>
								</div>
								
								<div class="mb-3">
									<small class="text-muted"><i class="bi bi-info-circle me-1"></i>Permissions highlighted in green are granted by selected roles.</small>
								</div>
								
								<div class="accordion" id="permissionsAccordion">
									@foreach ($permissions->whereNull('parent_id') as $grandparentPermission)
										<div class="accordion-item shadow-sm mb-2 border">
											<h2 class="accordion-header" id="heading{{ $grandparentPermission->id }}">
												<button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $grandparentPermission->id }}">
													<div class="form-check mb-0" onclick="event.stopPropagation()">
 														<input type="checkbox" name="permissions[]" value="{{ $grandparentPermission->id }}" class="form-check-input" id="permission{{ $grandparentPermission->id }}"
 															{{ in_array($grandparentPermission->id, $arr_permissions_all) ? 'checked' : '' }}>
														<label class="form-check-label fw-bold" for="permission{{ $grandparentPermission->id }}">{{ $grandparentPermission->translated_name }}</label>
													</div>
												</button>
											</h2>
											<div id="collapse{{ $grandparentPermission->id }}" class="accordion-collapse collapse" data-bs-parent="#permissionsAccordion">
												<div class="accordion-body bg-white border-top p-3">
													@if ($permissions->where('parent_id', $grandparentPermission->id)->count() > 0)
														<div class="form-check mb-3 pb-2 border-bottom">
 															<input type="checkbox" id="selectAll{{ $grandparentPermission->id }}" class="form-check-input">
															<label class="form-check-label font-italic" for="selectAll{{ $grandparentPermission->id }}">{{ __('locale.lbl_select_all_permissions') }} {{ $grandparentPermission->translated_name }}</label>
														</div>
													@endif

													@php
														$parentsWithChildren = $permissions->where('parent_id', $grandparentPermission->id)->filter(function($p) use ($permissions) {
															return $permissions->where('parent_id', $p->id)->count() > 0;
														});
														$parentsWithoutChildren = $permissions->where('parent_id', $grandparentPermission->id)->filter(function($p) use ($permissions) {
															return $permissions->where('parent_id', $p->id)->count() == 0;
														});
													@endphp

													@if ($parentsWithChildren->count() > 0)
														<div class="row">
															@foreach ($parentsWithChildren as $parentPermission)
																<div class="col-md-6 mb-3">
																	<div class="module-form-card card h-100 shadow-sm border">
																		<div class="card-header p-2 px-3 bg-white d-flex align-items-center justify-content-between">
																			<div class="form-check mb-0">
																				<input type="checkbox" name="permissions[]" value="{{ $parentPermission->id }}" class="form-check-input" id="permission{{ $parentPermission->id }}"
																					{{ in_array($parentPermission->id, $arr_permissions_all) ? 'checked' : '' }}>
																				<label class="form-check-label fw-bold" for="permission{{ $parentPermission->id }}">{{ $parentPermission->translated_name }}</label>
																			</div>
																			<button class="btn btn-sm btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $parentPermission->id }}">
																				<i class="bi bi-chevron-down"></i>
																			</button>
																		</div>
																		<div id="collapse{{ $parentPermission->id }}" class="collapse">
																			<div class="card-body p-3 pt-0">
																				<div class="form-check mb-2 mt-2">
																					<input type="checkbox" id="selectAll{{ $parentPermission->id }}" class="form-check-input">
																					<label class="form-check-label small" for="selectAll{{ $parentPermission->id }}">{{ __('locale.lbl_select_all_permissions') }}</label>
																				</div>
																				<div class="row row-cols-1 row-cols-sm-2 g-2">
																					@foreach ($permissions->where('parent_id', $parentPermission->id) as $childPermission)
																						<div class="col">
																							<div class="form-check form-switch permission-switch-card d-flex align-items-center gap-2 p-2 px-3 border rounded-3 bg-white h-100 shadow-xs {{ in_array($childPermission->id, $arr_permissions_all) ? 'is-active' : '' }}">
																								<input type="checkbox" name="permissions[]" value="{{ $childPermission->id }}" class="form-check-input m-0 flex-shrink-0" id="permission{{ $childPermission->id }}"
																									{{ in_array($childPermission->id, $arr_permissions_all) ? 'checked' : '' }}>
																								<label class="form-check-label small mb-0 cursor-pointer text-dark text-truncate" for="permission{{ $childPermission->id }}" title="{{ $childPermission->translated_name }}">{{ $childPermission->translated_name }}</label>
																							</div>
																						</div>
																					@endforeach
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															@endforeach
														</div>
													@endif

													@if ($parentsWithoutChildren->count() > 0)
														@if ($parentsWithChildren->count() > 0)
															<div class="border-top pt-3 mt-2 mb-3">
																<h6 class="text-secondary small fw-bold mb-3 d-flex align-items-center gap-1">
																	<i class="bi bi-list-check"></i> {{ __('locale.lbl_other_permissions') }}
																</h6>
															</div>
														@endif
														<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2 mb-3">
															@foreach ($parentsWithoutChildren as $parentPermission)
																<div class="col">
																	<div class="form-check form-switch permission-switch-card d-flex align-items-center gap-2 p-2 px-3 border rounded-3 bg-white h-100 shadow-xs {{ in_array($parentPermission->id, $arr_permissions_all) ? 'is-active' : '' }}">
																		<input type="checkbox" name="permissions[]" value="{{ $parentPermission->id }}" class="form-check-input m-0 flex-shrink-0" id="permission{{ $parentPermission->id }}"
																			{{ in_array($parentPermission->id, $arr_permissions_all) ? 'checked' : '' }}>
																		<label class="form-check-label fw-semibold small mb-0 cursor-pointer text-dark text-truncate" for="permission{{ $parentPermission->id }}" title="{{ $parentPermission->translated_name }}">{{ $parentPermission->translated_name }}</label>
																	</div>
																</div>
															@endforeach
														</div>
													@endif
												</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>

							<!-- Assignments Tab -->
							<div class="tab-pane fade" id="assignments" role="tabpanel">
								<div class="row mb-4">
									<div class="col-md-6">
										<label class="form-label fw-bold">{{ __('locale.lbl_list_groups') }}</label>
										<select name="client_id[]" id="group_id" class="form-select select2" multiple>
											@foreach ($groups as $group)
												<option value="{{ $group->id }}" @if (in_array($group->id, $assignedClientIds ?? [])) selected @endif>{{ $group->raison_sociale }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-6">
										<label class="form-label fw-bold">{{ __('locale.lbl_site_title') }}</label>
										<select name="client_id[]" id="site_id" class="form-select select2" multiple>
											@foreach ($sites as $site)
												<option value="{{ $site->id }}" @if (in_array($site->id, $assignedClientIds ?? [])) selected @endif>{{ $site->raison_sociale }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div id="portal-assignments-section" style="display: {{ $user->is_admin || $user->is_employee ? 'none' : 'block' }}">
									<h6 class="border-bottom pb-2 mb-3 fw-bold">{{ __('locale.client_portal_settings') }}</h6>
									<div id="portal-assignments-container">
										@foreach($userAssignments as $assignment)
											<div class="module-form-card card mb-3 portal-assignment-card" id="assignment-card-{{ $assignment->client_id }}" data-client-id="{{ $assignment->client_id }}">
												<div class="card-header d-flex justify-content-between align-items-center">
													<strong>{{ $assignment->client->raison_sociale ?? 'Client #' . $assignment->client_id }} ({{ $assignment->client->type ?? '' }})</strong>
													<div>
														<button type="button" class="btn btn-sm btn-outline-secondary btn-check-all" data-target="assignment-perms-{{ $assignment->client_id }}">
															{{ __('locale.lbl_select_all_permissions') }}
														</button>
														<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#assignment-perms-{{ $assignment->client_id }}">
															{{ __('locale.menu_permissions') }}
														</button>
													</div>
												</div>
												<div id="assignment-perms-{{ $assignment->client_id }}" class="collapse">
													<div class="card-body">
														<div class="row">
															@foreach($portalPermissionFields as $field)
																<div class="col-md-4 mb-2">
																	<div class="form-check form-switch">
																		<input class="form-check-input" type="checkbox" 
																			name="portal_permissions[{{ $assignment->client_id }}][{{ $field }}]" 
																			id="{{ $field }}-{{ $assignment->client_id }}"
																			{{ $assignment->$field ? 'checked' : '' }}>
																		<label class="form-check-label" for="{{ $field }}-{{ $assignment->client_id }}">
																			{{ __('locale.lbl_' . $field) }}
																		</label>
																	</div>
																</div>
															@endforeach
														</div>
													</div>
												</div>
											</div>
										@endforeach
										@if($userAssignments->isEmpty())
											<div class="alert alert-light border shadow-sm text-center py-4">
												<i class="bi bi-info-circle fs-4 d-block mb-2 text-info"></i>
												Select groups or sites above to configure their portal permissions.
											</div>
										@endif
									</div>
								</div>
							</div>

							<!-- Document Types Assignment Tab -->
							<div class="tab-pane fade" id="document-types" role="tabpanel">
								<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
									<div>
										<h6 class="fw-bold mb-1"><i class="bi bi-file-earmark-lock me-2 text-primary"></i>{{ __('locale.lbl_document_types_assignment') }}</h6>
										<p class="text-muted small mb-0">{{ __('locale.lbl_document_types_assignment_help') }}</p>
									</div>
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" id="select-all-document-types">
										<label class="form-check-label fw-semibold small" for="select-all-document-types">{{ __('locale.lbl_select_all_document_types') }}</label>
									</div>
								</div>

								<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="document-types-container">
									@forelse($clientDocumentTypes as $type)
										@php
											$isAssigned = in_array($type->id, old('client_document_types', $assignedDocumentTypeIds ?? []));
										@endphp
										<div class="col">
											<div class="module-form-card card h-100 border document-type-card {{ $isAssigned ? 'border-primary shadow-sm bg-light' : '' }}" style="cursor: pointer;">
												<div class="card-body p-3 d-flex align-items-start gap-2">
													<div class="form-check mt-1">
														<input class="form-check-input doc-type-checkbox" type="checkbox" 
															name="client_document_types[]" 
															value="{{ $type->id }}" 
															id="doc_type_{{ $type->id }}"
															{{ $isAssigned ? 'checked' : '' }}>
													</div>
													<label class="form-check-label flex-grow-1" for="doc_type_{{ $type->id }}" style="cursor: pointer;">
														<div class="d-flex align-items-center justify-content-between mb-1">
															<span class="fw-bold text-dark">{{ $type->name }}</span>
															<span class="badge bg-{{ $type->color ?? 'secondary' }}-subtle text-{{ $type->color ?? 'secondary' }} border border-{{ $type->color ?? 'secondary' }}-subtle rounded-pill">
																{{ $type->name }}
															</span>
														</div>
														@if($type->description)
															<p class="text-muted small mb-0">{{ $type->description }}</p>
														@endif
													</label>
												</div>
											</div>
										</div>
									@empty
										<div class="col-12 text-center text-muted py-4">
											<i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>
											{{ __('locale.no_document_types_available') }}
										</div>
									@endforelse
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>



			<div class="mt-4 pt-2 border-top">
				<button type="submit" class="btn btn-warning me-2">
					<i class="bi bi-pencil-square me-1"></i> {{ __('locale.btn_update_user') }}
				</button>
				<a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
					{{ __('locale.btn_cancel') }}
				</a>
			</div>
		</form>
	</div>
	</div>
@endsection

@section('scripts')
	<script>
		const nameInput = document.getElementById('name');
		const firstNameInput = document.getElementById('first_name');
		const lastNameInput = document.getElementById('last_name');

		const updateNameFromParts = () => {
			if (!nameInput || !firstNameInput || !lastNameInput) {
				return;
			}

			const first = firstNameInput.value.trim();
			const last = lastNameInput.value.trim();
			nameInput.value = [first, last].filter(Boolean).join(' ');
		};

		firstNameInput?.addEventListener('input', updateNameFromParts);
		lastNameInput?.addEventListener('input', updateNameFromParts);
		updateNameFromParts();

		// Improved Select All logic for permissions using jQuery delegation
		$(document).on('change', '#permissions .form-check-input[id^="selectAll"]', function() {
			const target = this;
			const isChecked = target.checked;

			// 1. Global Select All
			if (target.id === 'selectAllPermissions') {
				$('#permissionsAccordion .form-check-input').each(function() {
					if (this.checked !== isChecked) {
						this.checked = isChecked;
						$(this).trigger('change');
					}
				});
				return;
			}

			// 2. Section Select All (selectAllXX)
			const id = target.id.replace('selectAll', '');
			
			// Toggle the header checkbox for this section
			const headerCheckbox = document.getElementById('permission' + id);
			if (headerCheckbox && headerCheckbox.checked !== isChecked) {
				headerCheckbox.checked = isChecked;
				$(headerCheckbox).trigger('change');
			}

			// Toggle all checkboxes within this section's collapse
			const container = document.getElementById('collapse' + id);
			if (container) {
				$(container).find('.form-check-input').each(function() {
					if (this.checked !== isChecked) {
						this.checked = isChecked;
						$(this).trigger('change');
					}
				});
			}
		});

		const rolePermissionsMap = {
			@foreach($roles as $role)
				"{{ $role->id }}": @json($role->permissions->pluck('id')),
			@endforeach
		};

		function updatePermissionsHighlight() {
			document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
				const container = checkbox.closest('.form-check');
				if (container) {
					container.classList.remove('bg-success-subtle', 'border', 'border-success-subtle', 'rounded', 'px-2', 'py-1');
					container.title = '';
				}
			});

			const checkedRoleIds = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => cb.value);
			const allRolePermissions = new Set();
			checkedRoleIds.forEach(roleId => {
				if (rolePermissionsMap[roleId]) {
					rolePermissionsMap[roleId].forEach(pId => allRolePermissions.add(pId));
				}
			});

			document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
				const pId = parseInt(checkbox.value);
				if (allRolePermissions.has(pId)) {
					const container = checkbox.closest('.form-check');
					if (container) {
						container.classList.add('bg-success-subtle', 'border', 'border-success-subtle', 'rounded', 'px-2', 'py-1');
						container.title = "Granted by role";
					}
				}
			});
		}

		document.querySelectorAll('.role-checkbox').forEach(checkbox => {
			checkbox.addEventListener('change', updatePermissionsHighlight);
		});

		updatePermissionsHighlight();
		$('.select2').select2({
			placeholder: "{{ __('locale.lbl_select') }}",
			allowClear: true
		});

		// Portal Assignments Management
		const portalAssignmentsSection = document.getElementById('portal-assignments-section');
		const portalAssignmentsContainer = document.getElementById('portal-assignments-container');
		const isEmployeeCheckbox = document.getElementById('is_employee');
		const isAdminCheckbox = document.getElementById('is_admin');
		const groupIdSelect = $('#group_id');
		const siteIdSelect = $('#site_id');

		const allClients = {
			@foreach($groups as $group)
				"{{ $group->id }}": { name: @json($group->raison_sociale), type: "Group" },
			@endforeach
			@foreach($sites as $site)
				"{{ $site->id }}": { name: @json($site->raison_sociale), type: "Site" },
			@endforeach
		};

		const permissionFields = @json($portalPermissionFields);
		const permissionLabels = {
			@foreach($portalPermissionFields as $field)
				"{{ $field }}": @json(__('locale.lbl_' . $field)),
			@endforeach
		};

		const togglePortalSection = () => {
			if (isEmployeeCheckbox && isAdminCheckbox) {
				if (isEmployeeCheckbox.checked || isAdminCheckbox.checked) {
					portalAssignmentsSection.style.display = 'none';
				} else {
					portalAssignmentsSection.style.display = 'block';
				}
			}
		};

		if (isEmployeeCheckbox) isEmployeeCheckbox.addEventListener('change', togglePortalSection);
		if (isAdminCheckbox) isAdminCheckbox.addEventListener('change', togglePortalSection);
		togglePortalSection();

		const syncAssignments = () => {
			const selectedIds = [...(groupIdSelect.val() || []), ...(siteIdSelect.val() || [])];
			const existingCards = portalAssignmentsContainer.querySelectorAll('.portal-assignment-card');
			
			// Remove cards for deselected clients
			existingCards.forEach(card => {
				const clientId = card.getAttribute('data-client-id');
				if (!selectedIds.includes(clientId)) {
					card.remove();
				}
			});

			// Add cards for newly selected clients
			selectedIds.forEach(clientId => {
				if (!document.getElementById(`assignment-card-${clientId}`)) {
					const client = allClients[clientId];
					if (!client) return;

					const cardHtml = `
						<div class="module-form-card card mb-3 portal-assignment-card" id="assignment-card-${clientId}" data-client-id="${clientId}">
							<div class="card-header d-flex justify-content-between align-items-center">
								<strong>${client.name} (${client.type})</strong>
								<div>
									<button type="button" class="btn btn-sm btn-outline-secondary btn-check-all" data-target="assignment-perms-${clientId}">
										{{ __('locale.lbl_select_all_permissions') }}
									</button>
									<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#assignment-perms-${clientId}">
										{{ __('locale.menu_permissions') }}
									</button>
								</div>
							</div>
							<div id="assignment-perms-${clientId}" class="collapse show">
								<div class="card-body">
									<div class="row">
										${permissionFields.map(field => `
											<div class="col-md-4 mb-2">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" 
														name="portal_permissions[${clientId}][${field}]" 
														id="${field}-${clientId}" checked>
													<label class="form-check-label" for="${field}-${clientId}">
														${permissionLabels[field] || field}
													</label>
												</div>
											</div>
										`).join('')}
									</div>
								</div>
							</div>
						</div>
					`;
					portalAssignmentsContainer.insertAdjacentHTML('beforeend', cardHtml);
				}
			});
		};

		groupIdSelect.on('change', syncAssignments);
		siteIdSelect.on('change', syncAssignments);

		// Permission Search Functionality
		const permissionSearchInput = document.getElementById('permissionSearch');
		if (permissionSearchInput) {
			permissionSearchInput.addEventListener('input', function() {
				const query = this.value.toLowerCase().trim();
				const accordionItems = document.querySelectorAll('#permissionsAccordion .accordion-item');
				
				if (!query) {
					// Reset: show everything
					accordionItems.forEach(item => {
						item.style.display = '';
						item.querySelectorAll('.col-md-6').forEach(col => {
							col.style.display = '';
							col.querySelectorAll('.col').forEach(cItem => {
								cItem.style.display = '';
							});
						});
					});
					return;
				}

				accordionItems.forEach(gpItem => {
					let gpHasMatch = false;
					const gpName = gpItem.querySelector('.accordion-header .form-check-label').textContent.toLowerCase();
					
					// Check grandparent name
					if (gpName.includes(query)) gpHasMatch = true;

					const parentCols = gpItem.querySelectorAll('.col-md-6');
					parentCols.forEach(pCol => {
						let pHasMatch = false;
						const pName = pCol.querySelector('.card-header .form-check-label').textContent.toLowerCase();
						
						if (pName.includes(query)) pHasMatch = true;

						const childItems = pCol.querySelectorAll('.col');
						childItems.forEach(cItem => {
							const cName = cItem.querySelector('.form-check-label').textContent.toLowerCase();
							if (cName.includes(query)) {
								cItem.style.display = '';
								pHasMatch = true;
							} else {
								cItem.style.display = pName.includes(query) ? '' : 'none';
							}
						});

						if (pHasMatch || gpName.includes(query)) {
							pCol.style.display = '';
							gpHasMatch = true;
							// Expand parent collapse without animation conflicts
							const collapse = pCol.querySelector('.collapse');
							if (collapse && !collapse.classList.contains('show')) {
								collapse.classList.add('show');
							}
							const pButton = pCol.querySelector('[data-bs-toggle="collapse"]');
							if (pButton) {
								pButton.classList.remove('collapsed');
								pButton.setAttribute('aria-expanded', 'true');
							}
						} else {
							pCol.style.display = 'none';
						}
					});

					if (gpHasMatch) {
						gpItem.style.display = '';
						const collapse = gpItem.querySelector('.accordion-collapse');
						if (collapse && !collapse.classList.contains('show')) {
							collapse.classList.add('show');
						}
						const gpButton = gpItem.querySelector('.accordion-button');
						if (gpButton) {
							gpButton.classList.remove('collapsed');
							gpButton.setAttribute('aria-expanded', 'true');
						}
					} else {
						gpItem.style.display = 'none';
					}
				});
			});
		}

		// Delegation for "Check All" buttons
		portalAssignmentsContainer?.addEventListener('click', (e) => {
			if (e.target.classList.contains('btn-check-all')) {
				const targetId = e.target.getAttribute('data-target');
				const container = document.getElementById(targetId);
				const checkboxes = container.querySelectorAll('.form-check-input');
				const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
				checkboxes.forEach(cb => cb.checked = anyUnchecked);
			}
		});

		// Document Types Tab Management
		const docTypesNavItem = document.getElementById('document-types-nav-item');
		const docTypesTabBtn = document.getElementById('document-types-tab');
		const docPermissionIds = @json($documentPermissionIds ?? []);
		const selectAllDocTypes = document.getElementById('select-all-document-types');
		const docTypeCheckboxes = document.querySelectorAll('.doc-type-checkbox');
		const docTypesCountBadge = document.getElementById('document-types-count');

		function updateDocTypesTabVisibility() {
			if (!docTypesNavItem) return;

			const isAdmin = document.getElementById('is_admin')?.checked || {{ auth()->user()->isNovalyoAdmin() ? 'true' : 'false' }};
			let hasDocPermission = isAdmin;

			if (!hasDocPermission) {
				// Check direct permission checkboxes
				document.querySelectorAll('input[name="permissions[]"]:checked').forEach(cb => {
					if (docPermissionIds.includes(parseInt(cb.value))) {
						hasDocPermission = true;
					}
				});
			}

			if (!hasDocPermission) {
				// Check permissions via selected roles
				const checkedRoleIds = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => cb.value);
				for (const roleId of checkedRoleIds) {
					const rolePerms = rolePermissionsMap[roleId] || [];
					if (rolePerms.some(pId => docPermissionIds.includes(parseInt(pId)))) {
						hasDocPermission = true;
						break;
					}
				}
			}

			if (hasDocPermission) {
				docTypesNavItem.style.display = '';
			} else {
				docTypesNavItem.style.display = 'none';
				if (docTypesTabBtn && docTypesTabBtn.classList.contains('active')) {
					const rolesTabBtn = document.getElementById('roles-tab');
					if (rolesTabBtn) {
						bootstrap.Tab.getOrCreateInstance(rolesTabBtn).show();
					}
				}
			}
		}

		function updateDocTypeCount() {
			const checkedCount = document.querySelectorAll('.doc-type-checkbox:checked').length;
			if (docTypesCountBadge) {
				docTypesCountBadge.textContent = checkedCount;
			}
			if (selectAllDocTypes && docTypeCheckboxes.length > 0) {
				selectAllDocTypes.checked = checkedCount === docTypeCheckboxes.length;
				selectAllDocTypes.indeterminate = checkedCount > 0 && checkedCount < docTypeCheckboxes.length;
			}
		}

		// Document Type Cards styling & click handling
		document.querySelectorAll('.document-type-card').forEach(card => {
			card.addEventListener('click', (e) => {
				if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'label') {
					return;
				}
				const checkbox = card.querySelector('.doc-type-checkbox');
				if (checkbox) {
					checkbox.checked = !checkbox.checked;
					checkbox.dispatchEvent(new Event('change'));
				}
			});
		});

		docTypeCheckboxes.forEach(cb => {
			cb.addEventListener('change', () => {
				const card = cb.closest('.document-type-card');
				if (card) {
					if (cb.checked) {
						card.classList.add('border-primary', 'shadow-sm', 'bg-light');
					} else {
						card.classList.remove('border-primary', 'shadow-sm', 'bg-light');
					}
				}
				updateDocTypeCount();
			});
		});

		if (selectAllDocTypes) {
			selectAllDocTypes.addEventListener('change', () => {
				docTypeCheckboxes.forEach(cb => {
					cb.checked = selectAllDocTypes.checked;
					const card = cb.closest('.document-type-card');
					if (card) {
						if (cb.checked) {
							card.classList.add('border-primary', 'shadow-sm', 'bg-light');
						} else {
							card.classList.remove('border-primary', 'shadow-sm', 'bg-light');
						}
					}
				});
				updateDocTypeCount();
			});
		}

		// Listeners for role, permission, and admin changes
		document.querySelectorAll('.role-checkbox').forEach(cb => {
			cb.addEventListener('change', updateDocTypesTabVisibility);
		});
		document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
			cb.addEventListener('change', updateDocTypesTabVisibility);
		});
		document.getElementById('is_admin')?.addEventListener('change', updateDocTypesTabVisibility);

		// Permission switch card click & state handling
		document.querySelectorAll('.permission-switch-card').forEach(card => {
			card.addEventListener('click', (e) => {
				if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'label') {
					return;
				}
				const input = card.querySelector('input[type="checkbox"]');
				if (input) {
					input.checked = !input.checked;
					input.dispatchEvent(new Event('change', { bubbles: true }));
				}
			});
		});

		document.querySelectorAll('.permission-switch-card input[type="checkbox"]').forEach(input => {
			input.addEventListener('change', () => {
				const card = input.closest('.permission-switch-card');
				if (card) {
					if (input.checked) {
						card.classList.add('is-active');
					} else {
						card.classList.remove('is-active');
					}
				}
			});
		});

		// Initial check
		updateDocTypesTabVisibility();
		updateDocTypeCount();
	</script>

	<style>
		.permission-switch-card {
			transition: all 0.15s ease-in-out;
			border-color: #e2e8f0;
			cursor: pointer;
			padding-left: 0.75rem !important;
		}
		.permission-switch-card:hover {
			border-color: #008cd2;
			background-color: #f8fafc !important;
		}
		.permission-switch-card.is-active,
		.permission-switch-card:has(input:checked) {
			border-color: #008cd2;
			background-color: rgba(0, 140, 210, 0.06) !important;
		}
		.permission-switch-card .form-check-input {
			float: none !important;
			margin: 0 !important;
			cursor: pointer;
		}
		.permission-switch-card label {
			cursor: pointer;
		}
	</style>
@endsection
