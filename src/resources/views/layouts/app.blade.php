@extends('layouts.master')

@section('app_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'layout-fixed sidebar-expand-lg bg-body-tertiary')

@section('body_data', '')

@section('body')
    <!--begin::App Wrapper-->
    <div class="app-wrapper">

        {{-- Top Navbar --}}
        @include('partials.navbar.navbar')

        {{-- Left Main Sidebar --}}
        @include('partials.sidebar.left-sidebar')

        {{-- Content Wrapper --}}
        @include('partials.cwrapper.cwrapper')

        {{-- Footer --}}
        @hasSection('footer')
            @include('partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @include('partials.sidebar.right-sidebar')

    </div>
    <!--end::App Wrapper-->
@stop

@section('app_js')
    @stack('js')
    @yield('js')
@stop
