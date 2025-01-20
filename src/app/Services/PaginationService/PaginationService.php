<?php

namespace App\Services\PaginationService;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\PaginationService\PaginationServiceInterface;

class PaginationService implements PaginationServiceInterface {
    protected int $cacheDuration; // in minutes

    public function __construct() {
        $this->cacheDuration = config('pagination.cache_duration', 30);
    }

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
        int $perPage = PHP_INT_MAX,
        ?int $currentPage = null,
        ?string $path = null,
        ?array $options = []
    ): LengthAwarePaginator {
        // Handle Eloquent query builder
        if ($data instanceof Builder) {
            return $this->paginateQuery($data, $perPage, $currentPage, $path, $options);
        }

        // Handle Collection or Array
        if ($data instanceof Collection || is_array($data)) {
            return $this->paginateArray($data, $perPage, $currentPage, $path, $options);
        }

        throw new \InvalidArgumentException('Data must be an instance of Collection, array, or Builder.');
    }

    /**
     * Paginate Eloquent Query.
     *
     * @param Builder $query
     * @param int $perPage
     * @param int|null $currentPage
     * @param string|null $path
     * @param array|null $options
     * @return LengthAwarePaginator
     */
    protected function paginateQuery(
        Builder $query,
        int $perPage,
        ?int $currentPage,
        ?string $path,
        ?array $options
    ): LengthAwarePaginator {
        $currentPage = $currentPage ?? request()->get('page', 1);
        // Validate perPage and currentPage to prevent unreasonable values
        $perPage = $this->validatePerPage($perPage);
        $currentPage = $this->validateCurrentPage($currentPage);

        // Resolve options
        $columns = $options['columns'] ?? ['*'];
        $cacheEnabled = $options['cache'] ?? false;
        $cacheDuration = $options['cache_duration'] ?? $this->cacheDuration;

        if ($cacheEnabled) {
            $cacheKey = "pagination:query:" . md5(serialize([
                'query' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'perPage' => $perPage,
                'currentPage' => $currentPage,
                'columns' => $columns,
            ]));

            return Cache::remember($cacheKey, $cacheDuration, function () use ($query, $perPage, $currentPage, $path, $columns) {
                return $this->fetchQueryPagination($query, $columns, $perPage, $currentPage, $path);
            });
        }

        return $this->fetchQueryPagination($query, $columns, $perPage, $currentPage, $path);
    }

    /**
     * Fetch paginated results for an Eloquent query.
     *
     * @param Builder $query
     * @param array $columns
     * @param int $perPage
     * @param int $currentPage
     * @param string|null $path
     * @return LengthAwarePaginator
     */
    protected function fetchQueryPagination(
        Builder $query,
        array $columns,
        int $perPage,
        int $currentPage,
        ?string $path
    ): LengthAwarePaginator {
        $items = $query->select($columns)
            ->forPage($currentPage, $perPage)
            ->get();

        // $total = $query->count();
        $total = $query->toBase()->getCountForPagination();

        return $this->createPagination($items, $total, $perPage, $currentPage, $path);
    }

    /**
     * Paginate Collections or Arrays.
     *
     * @param array|Collection $data
     * @param int $perPage
     * @param int|null $currentPage
     * @param string|null $path
     * @param array|null $options
     * @return LengthAwarePaginator
     */
    protected function paginateArray(
        array|Collection $data,
        int $perPage,
        ?int $currentPage,
        ?string $path,
        ?array $options
    ): LengthAwarePaginator {
        $currentPage = $currentPage ?? request()->get('page', 1);
        // Validate perPage and currentPage to prevent unreasonable values
        $perPage = $this->validatePerPage($perPage);
        $currentPage = $this->validateCurrentPage($currentPage);
        $data = $data instanceof Collection ? $data : collect($data);

        // $items = $data->forPage($currentPage, $perPage);
        $chunkedData = $data->chunk($perPage);
        $items = $chunkedData->get($currentPage - 1, collect());

        $total = $data->count();

        return $this->createPagination($items, $total, $perPage, $currentPage, $path);
    }

    /**
     * Create pagination.
     *
     * @param Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param string|null $path
     * @return LengthAwarePaginator
     */
    protected function createPagination(Collection $items, int $total, int $perPage, int $currentPage, ?string $path): LengthAwarePaginator {
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $path ?? url()->current(), 'query' => Request::query()]
        );

        // return $this->formatPagination($paginator);
        return $paginator;
    }

    /**
     * Validate perPage parameter.
     *
     * @param int $perPage
     * @return int
     */
    protected function validatePerPage(int $perPage): int {
        $perPage = max(1, $perPage); // Ensure perPage is at least 1
        return $perPage;
    }

    /**
     * Validate currentPage parameter.
     *
     * @param int $currentPage
     * @return int
     */
    protected function validateCurrentPage(int $currentPage): int {
        $currentPage = max(1, $currentPage); // Ensure currentPage is at least 1
        return $currentPage;
    }

    /**
     * Format pagination response.
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public function formatPagination(LengthAwarePaginator $paginator): array {
        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'next_page_url' => $paginator->nextPageUrl(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'links' => $paginator->linkCollection()->toArray()
            ]
        ];
    }
}
