<?php

namespace App\Services\PageDetailsService;

interface PageDetailsServiceInterface {
   /**
     * Add a new detail to the page details array.
     *
     * @param string $key
     * @param array $properties
     * @return void
     */
    public function add(string $key, array $properties): void;

    /**
     * Get all page details.
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Get a specific detail by key.
     *
     * @param string $key
     * @return array|null
     */
    public function get(string $key): ?array;

    /**
     * Remove a specific detail by key.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;

    /**
     * Clear all stored details.
     */
    public function clear(): void;

    /**
     * Check if a page detail exists by key.
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * Sort the details by title.
     *
     * @param bool $ascending Whether to sort in ascending order. Default is true (ascending).
     * @return void
     */
    public function sortByTitle(bool $ascending = true): void;

    /**
     * Sort the details by their keys.
     *
     * @param bool $ascending Whether to sort in ascending order.
     * @return void
     */
    public function sortByKey(bool $ascending = true): void;

    /**
     * Paginate the page details.
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage = 10, int $page = 1): array;

    /**
     * Get all details as a nested structure (if applicable).
     *
     * @return array
     */
    public function getNestedDetails(): array;
}

