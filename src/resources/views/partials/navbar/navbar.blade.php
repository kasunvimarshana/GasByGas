@php( $def_container_class = 'container-fluid' )

<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="{{ $def_container_class }}">
        <!--begin::Start Navbar Links-->
        @include('partials.navbar.start')
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        @include('partials.navbar.end')
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
