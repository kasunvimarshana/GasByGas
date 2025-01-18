<?php

namespace App\Services\PaginationService;

use Illuminate\Pagination\LengthAwarePaginator;

interface PaginationServiceInterface {
    /**
     * Paginate data, works for Eloquent queries, collections, or arrays.
     *
     * @param mixed $data Query builder, collection, or array
     * @param int $perPage Items per page
     * @param int|null $currentPage Current page number
     * @param string|null $path Custom path for pagination links
     * @param array|null $options Additional customization options:
     *  - 'columns' (array): Columns to select for Eloquent queries
     *  - 'cache' (bool): Enable or disable caching (default: false)
     *  - 'cache_duration' (int): Cache duration in seconds (default: 60)
     * @return LengthAwarePaginator
     */
    public function paginate(
        mixed $data,
        int $perPage = 15,
        ?int $currentPage = null,
        ?string $path = null,
        ?array $options = []
    ): LengthAwarePaginator;

    /**
     * Format pagination response.
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function formatPagination(LengthAwarePaginator $paginator): array;
}
