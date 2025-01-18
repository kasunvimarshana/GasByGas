@if (!empty($breadcrumbs))
    <nav aria-label="breadcrumb float-sm-end">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb['url'] && !$loop->last)
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}">{{__($breadcrumb['title'])}}</a>
                    </li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{__($breadcrumb['title'])}}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
