@use('Illuminate\Support\Facades\Route')
{{-- @inject('request', 'Illuminate\Http\Request') --}}

@props(['item'])

@if ((empty($item['permission']) || Gate::allows($item['permission'])) && ($item['is_active']))
    @php
        $isActive = isset($item['route']) && Request::routeIs($item['route']); // Check active route
        $hasChildren = isset($item['children']) && !empty($item['children']);
        $hasActiveChild = $hasChildren && collect($item['children'])->contains(fn($child) => Request::routeIs($child['route']));
        $routeParameters = isset($item['parameters']) ? $item['parameters'] : [];
    @endphp

    <li class="nav-item {{ $hasChildren ? 'has-treeview' : '' }} {{ $isActive || $hasActiveChild ? 'menu-open' : '' }}">
        <a href="{{ $item['url'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">
            <i class="nav-icon {{ $item['icon'] }}"></i>
            <p>
                {{__($item['title'])}}
                @if ($hasChildren)
                    <i class="nav-arrow fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if ($hasChildren)
            <ul class="nav nav-treeview">
                @foreach ($item['children'] as $child)
                    <x-sidebar-menu-item :item="$child" />
                @endforeach
            </ul>
        @endif
    </li>
@endif
