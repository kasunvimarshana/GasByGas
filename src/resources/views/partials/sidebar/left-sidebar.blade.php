<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    @if(config('layout.logo_enabled'))
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="#" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="{!! asset(config('layout.logo_img')) !!}"
                alt="{{ config('layout.logo_img_alt') }}"
                class="{{ config('layout.logo_img_class') }}"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">{{ config('layout.logo') }}</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    @endif
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <x-sidebar />
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
{{-- asset('images/default-avatar.png') --}}
