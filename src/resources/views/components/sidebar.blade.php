@props(['menus' => null])

<!--begin::Sidebar Wrapper-->
<div class="sidebar-wrapper">
    <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-widget="treeview" data-accordion="false" >
            @foreach ($menus as $menu)
                <x-sidebar-menu-item :item="$menu" />
            @endforeach
        </ul>
        <!--end::Sidebar Menu-->
    </nav>
</div>
<!--end::Sidebar Wrapper-->
