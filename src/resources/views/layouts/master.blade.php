@inject('request', 'Illuminate\Http\Request')
@use('Illuminate\Support\Facades\Vite')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->
<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    {{-- <meta name="msapplication-TileColor" content="#ffffff" /> --}}
    {{-- <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}" /> --}}

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('layout.title_prefix', ''))
        @yield('title', config('layout.title', ''))
        @yield('title_postfix', config('layout.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre) --}}
    @yield('app_css_pre')

    {{-- Base Stylesheets (depends on Laravel asset bundling tool) --}}
    <!-- Fonts -->
    {{-- <link rel="preconnect" href="https://fonts.bunny.net" /> --}}
    {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> --}}
    {{-- @if(config('layout.google_fonts.allowed', true))
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" />
    @endif --}}

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('partials.plugins.plugins', ['type' => 'css'])

    {{-- <style>
        {!! Vite::content('resources/css/app.css') !!}
    </style>
    <script>
        {!! Vite::content('resources/js/app.js') !!}
    </script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @if(config('layout.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    <!-- Include PWA Partial -->
    @include('partials.pwa.pwa')

    {{-- Custom Stylesheets (post) --}}
    @yield('app_css')
</head>
<!--end::Head-->

<!--begin::Body-->
<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Extra Configured Plugins Scripts --}}
    @include('partials.plugins.plugins', ['type' => 'js'])
    {{-- Livewire Script --}}
    @if(config('layout.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('app_js')

</body>
<!--end::Body-->
</html>
