<!--begin::End Navbar Links-->
<ul class="navbar-nav ms-auto">
    <!--begin::Navbar Search-->
    {{-- @include('partials.navbar.search') --}}
    <!--end::Navbar Search-->

    <!--begin::Messages Dropdown Menu-->
    {{-- @include('partials.navbar.message-dropdown-menu') --}}
    <!--end::Messages Dropdown Menu-->

    <!--begin::Notifications Dropdown Menu-->
    {{-- @include('partials.navbar.notification-dropdown-menu') --}}
    <!--end::Notifications Dropdown Menu-->

    <!--begin::Fullscreen Toggle-->
    @include('partials.navbar.fullscreen-toggle')
    <!--end::Fullscreen Toggle-->

    <!--begin::User Menu Dropdown-->
    @if (auth()->check())
    @include('partials.navbar.user-menu-dropdown')
    @endif
    <!--end::User Menu Dropdown-->
  </ul>
<!--end::End Navbar Links-->
