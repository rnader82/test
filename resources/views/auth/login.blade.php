<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-semi-dark" data-assets-path="{{ asset('/') }}" data-template="vertical-menu-template-semi-dark"
	data-style="light">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Connexion | Novalyo</title>
		<meta name="description" content="Materialize – is the most developer friendly &amp; highly customizable Admin Dashboard Template." />
		<meta name="keywords" content="dashboard, material, material design, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="{{ asset('images/logo_small.png') }}" />
		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet">
		<!-- Icons -->
		<link href="{{ asset('fonts/remixicon/remixicon.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('fonts/flag-icons.css') }}" />
		<!-- Menu waves for no-customizer fix -->
		<link rel="stylesheet" href="{{ asset('libs/node-waves/node-waves.css') }}" />
		<!-- Core CSS -->
		<link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
		<link rel="stylesheet" href="{{ asset('css/theme-semi-dark.css') }}" class="template-customizer-theme-css" />
		<link rel="stylesheet" href="{{ asset('css/demo.css') }}" />
		<!-- Vendors CSS -->
		<link rel="stylesheet" href="{{ asset('libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
		<link rel="stylesheet" href="{{ asset('libs/typeahead-js/typeahead.css') }}" />
		<!-- Vendor -->
		<link rel="stylesheet" href="{{ asset('libs/@form-validation/form-validation.css') }}" />
		<!-- Page CSS -->
		<!-- Page -->
		<link rel="stylesheet" href="{{ asset('css/page-auth.css') }}">
		<!-- Helpers -->
		<script src="{{ asset('js/helpers.js') }}"></script>
		<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
		<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  --> {{-- <script src="{{ asset('js/template-customizer.js') }}"></script> --}}
		<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
		<script src="{{ asset('js/config.js') }}"></script>
	    <link rel="stylesheet" href="{{ asset('css/demo.css') }}?v=novalyo-v01" />
	</head>

	<body class="novalyo-portal novalyo-auth">
		<!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J3LMKC" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<!-- Content -->
		<div class="position-relative">
			<div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
				<div class="authentication-inner py-6">
					<!-- Login -->
					<div class="card p-md-7 p-1">
						<!-- Logo -->
						<div class="app-brand justify-content-center mt-5">
							<img src="{{ asset('images/logo.png') }}" style="width: 193px; max-width: 100%;" alt="Novalyo" />
						</div>
						<!-- /Logo -->
						<div class="card-body mt-1">
                            <h4 class="mb-5">{{ __('locale.portal.login_heading') }}</h4>
							<form id="formAuthentication" class="mb-5" action="{{ route('login') }}" method="POST"> @csrf @if ($errors->any())
									<div class="alert alert-danger">
										<strong>{{ __('locale.portal.validation_error') }}</strong> <ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif
								<div class="form-floating form-floating-outline mb-5">
									<input type="text" class="form-control" id="email" name="email" placeholder="{{ __('locale.portal.email') }}" autofocus>
									<label for="email">{{ __('locale.portal.email') }}</label>
								</div>
								<div class="mb-5">
									<div class="form-password-toggle">
										<div class="input-group input-group-merge">
											<div class="form-floating form-floating-outline">
												<input type="password" id="password" class="form-control" name="password"
													placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
												<label for="password">{{ __('locale.portal.password') }}</label>
											</div>
											<span class="input-group-text cursor-pointer"><i class="ri-eye-off-line" style="font-size: 1rem !important;"></i></span>
										</div>
									</div>
								</div>
								<div class="mb-5 d-flex justify-content-between mt-5">
									<div class="form-check mt-2">
										<input class="form-check-input" type="checkbox" id="remember-me">
										<label class="form-check-label" for="remember-me"> {{ __('locale.portal.remember') }} </label>
									</div> {{-- <a href="auth-forgot-password-basic.html" class="float-end mb-1 mt-2">
                                    <span>Forgot Password?</span>
                                </a> --}}
								</div>
								<div class="mb-5">
									<button class="btn btn-primary d-grid w-100" type="submit">{{ __('locale.portal.login') }}</button>
								</div>
							</form> {{-- <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="{{ route('register') }}">
                                <span>Create an account</span>
                            </a>
                        </p> --}}
						</div>
					</div>
					<!-- /Login --> {{-- <img alt="mask" src="../../assets/img/illustrations/auth-basic-login-mask-light.png" class="authentication-image d-none d-lg-block"
                    data-app-light-img="illustrations/auth-basic-login-mask-light.png" data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" /> --}}
				</div>
			</div>
		</div>
		<!-- / Content -->
		<!-- Core JS -->
		<!-- build:js assets/vendor/js/core.js -->
		<script src="{{ asset('libs/jquery/jquery.js') }}"></script>
		<script src="{{ asset('libs/popper/popper.js') }}"></script>
		<script src="{{ asset('js/bootstrap.js') }}"></script>
		<script src="{{ asset('libs/node-waves/node-waves.js') }}"></script>
		<script src="{{ asset('libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
		<script src="{{ asset('libs/hammer/hammer.js') }}"></script>
		<script src="{{ asset('libs/i18n/i18n.js') }}"></script>
		<script src="{{ asset('libs/typeahead-js/typeahead.js') }}"></script>
		<script src="{{ asset('js/menu.js') }}"></script>
		<!-- endbuild -->
		<!-- Vendors JS -->
		<script src="{{ asset('libs/@form-validation/popular.js') }}"></script>
		<script src="{{ asset('libs/@form-validation/bootstrap5.js') }}"></script>
		<script src="{{ asset('libs/@form-validation/auto-focus.js') }}"></script>
		<!-- Main JS -->
		<script src="{{ asset('js/main.js') }}?v=novalyo-v01"></script>
		<!-- Page JS -->
		<script src="{{ asset('js/pages-auth.js') }}"></script>
	</body>

</html>
<!-- beautify ignore:end -->
