<?php

namespace App\Services\PageDetailsService;

use App\Services\PageDetailsService\PageDetailsServiceInterface;

class PageDetailsService implements PageDetailsServiceInterface {
    private array $details = [];

    /**
     * Add a new detail to the page details array.
     *
     * @param string $key
     * @param array $properties
     * @return void
     */
    public function add(string $key, array $properties): void {
        // $this->details[$key] = array_merge($this->details[$key] ?? [], $properties);
        $this->details[$key] = $properties;
    }

    /**
     * Get all page details.
     *
     * @return array
     */
    public function getAll(): array {
        return $this->details;
    }

    /**
     * Get a specific detail by key.
     *
     * @param string $key
     * @return array|null
     */
    public function get(string $key): ?array {
        return $this->details[$key] ?? null;
    }

    /**
     * Remove a specific detail by key.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void {
        unset($this->details[$key]);
    }

    /**
     * Clear all stored details.
     */
    public function clear(): void {
        $this->details = [];
    }

    /**
     * Check if a page detail exists by key.
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool {
        return isset($this->details[$key]);
    }

    /**
     * Sort the details by title.
     *
     * @param bool $ascending Whether to sort in ascending order. Default is true (ascending).
     * @return void
     */
    public function sortByTitle(bool $ascending = true): void {
        uasort($this->details, function ($a, $b) use ($ascending) {
            $comparison = strcmp($a['title'], $b['title']);
            return $ascending ? $comparison : -$comparison;
        });
    }

    /**
     * Sort the details by their keys.
     *
     * @param bool $ascending Whether to sort in ascending order.
     * @return void
     */
    public function sortByKey(bool $ascending = true): void {
        if ($ascending) {
            ksort($this->details); // Sort in ascending order by key
        } else {
            krsort($this->details); // Sort in descending order by key
        }
    }

    /**
     * Paginate the page details.
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage = 10, int $page = 1): array {
        $offset = ($page - 1) * $perPage;
        return array_slice($this->details, $offset, $perPage);
    }

    /**
     * Get all details as a nested structure (if applicable).
     *
     * @return array
     */
    public function getNestedDetails(): array {
        $nestedDetails = [];
        foreach ($this->details as $key => $properties) {
            if (isset($properties['children'])) {
                $nestedDetails[$key] = [
                    'properties' => $properties,
                    'children' => $properties['children'],
                ];
            } else {
                $nestedDetails[$key] = $properties;
            }
        }
        return $nestedDetails;
    }

}
