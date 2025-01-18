<?php

namespace App\Services\NavigationService;

use Illuminate\Support\Facades\Route;
use App\Services\NavigationService\BaseNavigationService;
use App\Models\BreadcrumbItem;

class BreadcrumbService extends BaseNavigationService {
    protected string $modelClass = BreadcrumbItem::class;

    public function __construct(string $type = '') {
        parent::__construct($type);
    }

    /**
     * Build Breadcrumbs Based on Current Route.
     */
    protected function buildItems(): array {
        // Get current route name
        $currentRouteName = Route::currentRouteName();
        // $currentRouteParameters = Route::current()->parameters();
        $trail = [];

        $query = $this->modelClass::with(['children']);
        $currentItem = $query->where('route', $currentRouteName)
            ->when($this->type != '', function ($q) {
                return $q->whereJsonContains('types', $this->type);
            })
            ->first();

        while ($currentItem) {
            $trail[] = $this->mapItem($currentItem);

            $currentItem = $currentItem->parent;
        }

        return array_reverse($trail);
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
            'parameters' => $parameters,
        ];
    }
}
