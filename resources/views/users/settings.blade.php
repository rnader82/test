@extends('layouts.app')
@section('portal-module', 'users')
@section('portal-page', 'settings')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')
 @section('content')
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<div class="col-12 col-lg-8">
				<div class="module-form-card card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">{{ __('locale.lbl_user_settings') }}</h5>
					</div>
					<div class="card-body">
						@if (session('status'))
							<div class="alert alert-success" role="alert"> {{ session('status') }} </div>
						@endif
						<form method="POST"
							action="{{ route('user.settings.update') }}"> @csrf @method('PUT') <div class="mb-4">
								<label class="form-label d-block fw-semibold">{{ __('locale.lbl_number_format') }}</label>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="number_format" id="number-format-american" value="american"
										{{ old('number_format', $user->number_format) === 'american' ? 'checked' : '' }}> <label class="form-check-label" for="number-format-american">
										{{ __('locale.lbl_number_format_american') }} </label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="number_format" id="number-format-french" value="french"
										{{ old('number_format', $user->number_format) === 'french' ? 'checked' : '' }}> <label class="form-check-label" for="number-format-french">
										{{ __('locale.lbl_number_format_french') }} </label>
								</div> @error('number_format')
									<div class="text-danger small mt-2">{{ $message }}</div>
								@enderror
							</div>
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-save me-2"></i> {{ __('locale.btn_save_changes') }} </button>
						</form>
					</div>
				</div>
				<div class="module-form-card card mt-4">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="mb-0">{{ __('locale.lbl_change_password') }}</h5>
					</div>
					<div class="card-body">
						<form method="POST" action="{{ route('user.settings.password') }}">
							@csrf
							@method('PUT')
							<div class="mb-3">
								<label class="form-label fw-semibold" for="current-password">{{ __('locale.lbl_current_password') }}</label>
								<input type="password" name="current_password" id="current-password" class="form-control" required autocomplete="current-password">
								@error('current_password')
									<div class="text-danger small mt-2">{{ $message }}</div>
								@enderror
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold" for="password">{{ __('locale.lbl_new_password') }}</label>
								<input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
								@error('password')
									<div class="text-danger small mt-2">{{ $message }}</div>
								@enderror
							</div>
							<div class="mb-4">
								<label class="form-label fw-semibold" for="password-confirmation">{{ __('locale.lbl_confirm_password') }}</label>
								<input type="password" name="password_confirmation" id="password-confirmation" class="form-control" required autocomplete="new-password">
								@error('password_confirmation')
									<div class="text-danger small mt-2">{{ $message }}</div>
								@enderror
							</div>
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-key me-2"></i> {{ __('locale.lbl_change_password') }}
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
