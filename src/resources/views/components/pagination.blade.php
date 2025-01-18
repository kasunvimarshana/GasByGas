<nav aria-label="Page navigation">
    <ul class="pagination justify-content-{{ $alignment }}">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">{{__('messages.pagination_previous')}}</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{!! $paginator->previousPageUrl() !!}" aria-label="{{__('messages.pagination_previous')}}">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        @endif

        {{-- Pagination Links --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link" href="{!! $url !!}">{{ $page }}</a>
            </li>
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{!! $paginator->nextPageUrl() !!}" aria-label="{{__('messages.pagination_next')}}">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">{{__('messages.pagination_next')}}</span>
            </li>
        @endif
    </ul>
</nav>
