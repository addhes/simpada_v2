<!-- Shortcut Icon -->

<link rel="shortcut icon" href="{{ URL::asset('assets/images/logo.png') }}">

@yield('css')
<!-- App css -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
<script src="https://kit.fontawesome.com/4d227a6f88.js" crossorigin="anonymous"></script>
<script src="{{ URL::asset('assets/js/cleave.min.js') }}"></script>

@if(isset($isDark) && $isDark)
<link href="{{ URL::asset('assets/css/backend-dark.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" />
@else
<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
@endif