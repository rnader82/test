<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-bordered" data-assets-path="{{ asset('/') }}" data-template="vertical-menu-template-bordered"
	data-style="light">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Inscription | Novalyo</title>
		<meta name="description" content="Materialize – is the most developer friendly &amp; highly customizable Admin Dashboard Template." />
		<meta name="keywords" content="dashboard, material, material design, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
		<!-- Canonical SEO -->
		<link rel="canonical" href="https://1.envato.market/materialize_admin">
		<!-- ? PROD Only: Google Tag Manager (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
		<script>
			(function(w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start': new Date().getTime(),
					event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-5J3LMKC');
		</script>
		<!-- End Google Tag Manager -->
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="{{ asset('images/logo_small.png') }}" />
		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet">
		<!-- Icons -->
		<link rel="stylesheet" href="{{ asset('fonts/remixicon/remixicon.css') }}" />
		<link rel="stylesheet" href="{{ asset('fonts/flag-icons.css') }}" />
		<!-- Menu waves for no-customizer fix -->
		<link rel="stylesheet" href="{{ asset('libs/node-waves/node-waves.css') }}" />
		<!-- Core CSS -->
		<link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
		<link rel="stylesheet" href="{{ asset('css/theme-bordered.css') }}" class="template-customizer-theme-css" />
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
		<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
		<script src="{{ asset('js/config.js') }}?v=novalyo-html-v02"></script>
	    <link rel="stylesheet" href="{{ asset('css/demo.css') }}?v=novalyo-html-v04" />
	</head>

	<body class="novalyo-portal novalyo-auth">
		<!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5J3LMKC" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<!-- Content -->
		<div class="position-relative">
			<div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
				<div class="authentication-inner py-6">
					<!-- Register Card -->
					<div class="card p-md-7 p-1">
						<!-- Logo -->
						<div class="app-brand justify-content-center mt-5">
							<a href="{{ url('/') }}" class="app-brand-link"><img src="{{ asset('images/logo.png') }}" alt="Novalyo" style="width: 193px; max-width: 100%;"></a>
						</div>
						@if ($errors->any())
							<div class="alert alert-danger">
								<strong>{{ __('locale.portal.validation_error') }}</strong> <ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						<!-- /Logo -->
						<div class="card-body mt-1">
							<h4 class="mb-1">{{ __('locale.portal.register_heading') }}</h4>
							<p class="mb-5">{{ __('locale.portal.register_intro') }}</p>
							<form id="formAuthentication" class="mb-5" action="{{ route('register') }}" method="POST"> @csrf <div class="form-floating form-floating-outline mb-5">
									<input type="text" class="form-control" id="name" name="name" placeholder="{{ __('locale.portal.name') }}" autofocus>
									<label for="name">{{ __('locale.portal.name') }}</label>
								</div>
								<div class="form-floating form-floating-outline mb-5">
									<input type="text" class="form-control" id="email" name="email" placeholder="{{ __('locale.portal.email') }}">
									<label for="email">{{ __('locale.portal.email') }}</label>
								</div>
								<div class="mb-5 form-password-toggle">
									<div class="input-group input-group-merge">
										<div class="form-floating form-floating-outline">
											<input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
												aria-describedby="password" />
											<label for="password">{{ __('locale.portal.password') }}</label>
										</div>
										<span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
									</div>
								</div>
								<div class="mb-5 form-password-toggle">
									<div class="input-group input-group-merge">
										<div class="form-floating form-floating-outline">
											<input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
												placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
											<label for="password_confirmation">{{ __('locale.portal.confirm_password') }}</label>
										</div>
										<span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
									</div>
								</div>
								<div class="mb-5">
									<div class="form-check mt-2">
										<input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
										<label class="form-check-label" for="terms-conditions"> {{ __('locale.portal.terms_agree') }} <a href="javascript:void(0);">{{ __('locale.portal.terms_link') }}</a>
										</label>
									</div>
								</div>
								<button class="btn btn-primary d-grid w-100">{{ __('locale.portal.sign_up') }}</button>
							</form>
							<p class="text-center">
								<span>{{ __('locale.portal.already_account') }}</span>
								<a href="{{ route('login') }}">
									<span>{{ __('locale.portal.login') }}</span>
								</a>
							</p>
						</div>
					</div>
					<!-- Register Card -->

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
		<script src="{{ asset('js/main.js') }}?v=novalyo-html-v02"></script>
		<!-- Page JS -->
		<script src="{{ asset('js/pages-auth.js') }}"></script>
	</body>

</html>
