<?php

namespace App\Services\NavigationService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Services\NavigationService\Contracts\NavigationServiceInterface;
use App\Models\NavigationItem;

/**
 * Abstract class BaseNavigationService
 *
 * Provides core functionality for managing navigation items, including:
 * - Caching
 * - Query filtering and sorting
 * - User permission checks
 * - Breadcrumb generation
 */
abstract class BaseNavigationService implements NavigationServiceInterface {
    protected string $modelClass = NavigationItem::class;
    protected string $cacheKey;
    protected ?string $type = '';
    protected bool $cacheEnabled = false;
    protected int $cacheDuration = 60; // in minutes
    protected array $defaultPermissions = [];

    protected array $items = [];

    public function __construct(string $type = '') {
        $this->type = $type;
        $this->cacheEnabled = config('navigation.cache_enabled', false);
        $this->cacheDuration = config('navigation.cache_ttl', 60);
        $this->defaultPermissions = config('navigation.default_permissions', []);
        $this->cacheKey = $this->generateCacheKey();
    }

    /**
     * Retrieve navigation items, either from cache or freshly built.
     *
     * @return array The array of navigation items.
     */
    public function getItems(): array {
        if ($this->cacheEnabled) {
            return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
                return $this->buildItems();
            });
        }

        return $this->buildItems();
    }

    /**
     * Generate a cache key for the navigation based on type.
     *
     * @return string The generated cache key.
     */
    protected function generateCacheKey(): string {
        return "navigation" . ($this->type ? "_{$this->type}" : "");
    }

    /**
     * Build navigation items with applied filters, permissions, and sorting.
     *
     * @return array The navigation items array.
     */
    protected function buildItems(): array {
        $query = $this->modelClass::with(['children']);

        $query = $this->applyQueryFilter($query);

        $query = $this->applySortingRules($query);

        $items = $query->get()
            ->filter(fn($item) => $this->userHasPermissionOrAnyChild($item))
            ->map(fn($item) => $this->mapItem($item))
            ->toArray();
        return $items;
    }

    /**
     * Apply the filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyQueryFilter($query) {
        $query->whereNull('parent_id');
        $query->when($this->type != '', function ($q) {
            return $q->whereJsonContains('types', $this->type);
        });
        $query->where('is_active', true);

        return $query;
    }

    /**
     * Define the default sorting rules for navigation items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applySortingRules($query) {
        $query->orderBy('order');

        return $query;
    }

    /**
     * Map navigation item with additional properties (is_active, has_active_child, etc.).
     *
     * @param mixed $item The navigation item instance.
     * @return array The mapped navigation item.
     */
    protected function mapItem($item): array {
        $parameters =  $item->parameters ?? [];
        $url = ($item->route) ? route($item->route, $parameters) : '#';

        return [
            'id' => $item->id,
            'title' => $item->title,
            'route' => $item->route,
            'url' => $url,
            'icon' => $item->icon,
            'parameters' => $item->parameters,
            'permission' => $item->permission,
            'is_active' => $item->is_active, // $this->isRouteActive($item)
            'has_active_child' => $this->hasActiveChild($item),
            // 'breadcrumbs' => $this->generateBreadcrumbs($item),
            'children' => $item->children->map(fn($child) => $this->mapItem($child))->toArray(),
        ];
    }

    /**
     * Add a custom navigation item.
     *
     * @param string $label The navigation item label.
     * @param string $url The URL for the navigation item.
     * @return self Returns the instance for chaining.
     */
    public function addCustom(string $label, string $url): self {
        // $this->items[] = ['label' => $label, 'url' => $url];
        $this->items[] = compact('label', 'url');
        return $this;
    }

    /**
     * Check if the user has permission for a navigation item.
     *
     * @param mixed $item The navigation item instance.
     * @return bool True if the user has permission, false otherwise.
     */
    public function userHasPermission($item): bool {
        /*
        $userPermissions = Auth::user()?->permissions ?? $this->defaultPermissions;
        return empty($item->permission) || array_intersect((array) $item->permission, $userPermissions);
        */
        // to do: need to check is one of child has permission
        return empty($item->permission) || Gate::allows($item->permission);
    }

    /**
     * Check if the current user has permission for the item or any of its child navigation items.
     *
     * @param mixed $navigationItem The navigation item instance.
     * @return bool True if the user has permission for the item or any of its children.
     */
    public function userHasPermissionOrAnyChild($item): bool {
        // First, check if the current item has permission
        if ($this->userHasPermission($item)) {
            return true;
        }

        // Recursively check each child to see if any child or descendant has permission
        return $item->children->contains(fn($child) =>
            $this->userHasPermissionOrAnyChild($child)
        );
    }

    /**
     * Generate breadcrumbs based on navigation hierarchy.
     *
     * @param mixed $item The navigation item instance.
     * @return array The breadcrumbs array.
     */
    public function generateBreadcrumbs($item): array {
        $breadcrumbs = [];
        while ($item) {
            $parameters =  $item->parameters ?? [];
            $url = ($item->route) ? route($item->route, $parameters) : '#';
            $breadcrumbs[] = [
                'title' => $item->title,
                'route' => $item->route,
                'url' => $url,
                'icon' => $item->icon,
                'parameters' => $parameters,
            ];
            $item = $item->parent;
        }

        return array_reverse($breadcrumbs);
    }

    /**
     * Check if the current route matches the navigation item's route.
     *
     * @param mixed $item The navigation item instance.
     * @return bool True if active, false otherwise.
     */
    public function isRouteActive($item): bool {
        /*
        // return Request::routeIs($item->route);
        // return Route::current()->is($item->route);
        if (!$item->route) {
            return false;
        }

        $routeName = Route::currentRouteName();
        // $parameters = Route::current()->parameters();

        return $routeName === $item->route;
        */
        return $item->route && request()->routeIs($item->route);
    }

    /**
     * Check if the navigation item has any active child items.
     *
     * @param mixed $item The navigation item instance.
     * @return bool True if an active child exists, false otherwise.
     */
    public function hasActiveChild($item): bool {
        // return request()->is("{$item->route}*")
        return $item->children->contains(fn($child) =>
            $this->isRouteActive($child) || $this->hasActiveChild($child)
        );
    }
}
