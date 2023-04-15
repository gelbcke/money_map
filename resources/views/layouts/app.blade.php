<!DOCTYPE html>
<html lang="{{ Auth::user()->language ?? app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Extra details for Live View on GitHub Pages -->
	<title>{{ config('app.name') }} - {{ $namePage }}</title>
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets') }}/dist/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets') }}/dist/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets') }}/dist/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="{{ asset('assets') }}/dist/img/favicon/site.webmanifest">
	<link rel="mask-icon" href="{{ asset('assets') }}/dist/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="theme-color" content="#ffffff">
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/flag-icon-css/css/flag-icon.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('assets') }}/dist/css/adminlte.min.css">
	@yield('styles')
</head>

<body class="{{ $body_class ?? Auth::user()->theme }}">
	@auth
		@include('layouts.page_template.auth')
	@endauth
	@guest
		@include('layouts.page_template.guest')
	@endguest

	<!-- jQuery -->
	<script src="{{ asset('assets') }}/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ asset('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	@stack('js')
	@yield('scripts')
	<!-- AdminLTE App -->
	<script src="{{ asset('assets') }}/dist/js/adminlte.min.js"></script>
</body>

</html>
