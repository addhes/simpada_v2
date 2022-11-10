<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />

    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ setting('meta_description') }}">
    <meta name="keyword" content="{{ setting('meta_keyword') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('before-styles')
    @php
    $isDark=false;
    @endphp
    @if(isset($isDark) && $isDark)
    @include('layouts.shared.head', ['isDark' => true])
    @elseif(isset($isRTL) && $isRTL)
    @include('layouts.shared.head', ['isRTL' => true])
    @else
    @include('layouts.shared.head')
    @endif
    @stack('after-styles')
</head>

@if(isset($isScrollable) && $isScrollable)

<body class="scrollable-layout">
    @elseif(isset($isBoxed) && $isBoxed)

    <body class="left-side-menu-condensed boxed-layout" data-left-keep-condensed="true">
        @elseif(isset($isDarkSidebar) && $isDarkSidebar)

        <body class="left-side-menu-dark">
            @elseif(isset($isCondensedSidebar) && $isCondensedSidebar)

            <body class="left-side-menu-condensed" data-left-keep-condensed="true">
                @else

                <body>
                    @endif

                    @if(isset($withLoader) && $withLoader)
                    <!-- Pre-loader -->
                    <div id="preloader">
                        <div id="status">
                            <div class="spinner">
                                <div class="circle1"></div>
                                <div class="circle2"></div>
                                <div class="circle3"></div>
                            </div>
                        </div>
                    </div>
                    <!-- End Preloader-->
                    @endif
                    <div id="wrapper">

                        @include('layouts.shared.header')
                        @include('layouts.shared.sidebar')

                        <div class="content-page">
                            <div class="content">
                                <!-- Start Content-->
                                <div class="container-fluid">

                                    @yield('breadcrumb')
                                    @include('flash::message')
                                    @yield('content')
                                </div>
                            </div>

                            @include('layouts.shared.footer')
                        </div>
                    </div>

                    @include('layouts.shared.rightbar')
                    @include('layouts.shared.footer-script')
                    @if (getenv('APP_ENV') === 'local')
                    <script id="__bs_script__">
                    //<![CDATA[
                    document.write(
                        "<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.26.7'><\/script>"
                        .replace("HOST", location.hostname));
                    //]]>
                    </script>
                    @endif
                </body>

</html>