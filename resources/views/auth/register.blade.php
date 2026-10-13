<!DOCTYPE html>
<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-bordered" data-assets-path="{{ asset('/') }}" data-template="vertical-menu-template-bordered"
	data-style="light">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
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
		<script src="{{ asset('js/config.js') }}"></script>
	</head>

	<body>
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
							<a href="index.html" class="app-brand-link gap-2">
								<span class="app-brand-logo demo">
									<span style="color:var(--bs-primary);">
										<svg width="268" height="150" viewBox="0 0 38 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path
												d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
												fill="currentColor" />
											<path
												d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
												fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4" />
											<path
												d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
												fill="currentColor" />
											<path
												d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
												fill="currentColor" />
											<path
												d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
												fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4" />
											<path
												d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
												fill="currentColor" />
											<defs>
												<linearGradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138" x2="10.532" y2="24.104" gradientUnits="userSpaceOnUse">
													<stop offset="0" stop-opacity="1" />
													<stop offset="1" stop-opacity="0" />
												</linearGradient>
												<linearGradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139" x2="10.3357" y2="24.1155" gradientUnits="userSpaceOnUse">
													<stop offset="0" stop-opacity="1" />
													<stop offset="1" stop-opacity="0" />
												</linearGradient>
											</defs>
										</svg>
									</span>
								</span>
								<span class="app-brand-text demo text-heading fw-semibold">Materialize</span>
							</a>
						</div>
						@if ($errors->any())
							<div class="alert alert-danger">
								<strong>Whoops!</strong> There were some problems with your input. <ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						<!-- /Logo -->
						<div class="card-body mt-1">
							<h4 class="mb-1">Adventure starts here 🚀</h4>
							<p class="mb-5">Make your app management easy and fun!</p>
							<form id="formAuthentication" class="mb-5" action="{{ route('register') }}" method="POST"> @csrf <div class="form-floating form-floating-outline mb-5">
									<input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" autofocus>
									<label for="name">Name</label>
								</div>
								<div class="form-floating form-floating-outline mb-5">
									<input type="text" class="form-control" id="email" name="email" placeholder="Enter your email">
									<label for="email">Email</label>
								</div>
								<div class="mb-5 form-password-toggle">
									<div class="input-group input-group-merge">
										<div class="form-floating form-floating-outline">
											<input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
												aria-describedby="password" />
											<label for="password">Password</label>
										</div>
										<span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
									</div>
								</div>
								<div class="mb-5 form-password-toggle">
									<div class="input-group input-group-merge">
										<div class="form-floating form-floating-outline">
											<input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
												placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
											<label for="password_confirmation">Confirm Password</label>
										</div>
										<span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
									</div>
								</div>
								<div class="mb-5">
									<div class="form-check mt-2">
										<input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
										<label class="form-check-label" for="terms-conditions"> I agree to <a href="javascript:void(0);">privacy policy & terms</a>
										</label>
									</div>
								</div>
								<button class="btn btn-primary d-grid w-100"> Sign up </button>
							</form>
							<p class="text-center">
								<span>Already have an account?</span>
								<a href="{{ route('login') }}">
									<span>Sign in instead</span>
								</a>
							</p>
						</div>
					</div>
					<!-- Register Card -->
					<img alt="mask" src="../../assets/img/illustrations/auth-basic-register-mask-light.png" class="authentication-image d-none d-lg-block"
						data-app-light-img="illustrations/auth-basic-register-mask-light.png" data-app-dark-img="illustrations/auth-basic-register-mask-dark.png" />
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
		<script src="{{ asset('js/main.js') }}"></script>
		<!-- Page JS -->
		<script src="{{ asset('js/pages-auth.js') }}"></script>
	</body>

</html>
