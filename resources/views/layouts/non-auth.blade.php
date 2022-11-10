<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ setting('meta_description') }}">
    <meta name="keyword" content="{{ setting('meta_keyword') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <!-- <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="icon" type="image/ico" href="{{asset('assets/images/favicon.png')}}" /> -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo.png') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" />

    @include('layouts.shared.head')
    <style>
    .danger {
        color: red;
    }
    </style>
</head>

<body class="authentication-bg">
    @yield('content')
    @include('layouts.shared.footer-script')
</body>

</html>