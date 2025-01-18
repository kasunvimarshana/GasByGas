@php( $def_container_class = 'container-fluid' )

{{-- Default Content Wrapper --}}
<!--begin::App Main-->
<main class="app-main">

    {{-- Preloader Animation (cwrapper mode) --}}
    @include('partials.common.preloader')

    {{-- Content Header --}}
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="{{ $def_container_class }}">
            @yield('content_header')
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    {{-- Main Content --}}
    <!--begin::App Content-->
    <div class="app-content">
        <div class="{{ $def_container_class }}">
            @stack('content')
            @yield('content')
        </div>
    </div>
    <!--end::App Content-->

</main>
<!--end::App Main-->
