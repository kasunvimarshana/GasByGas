<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\NavigationService\SidebarService;

class Sidebar extends Component {
    public $menus;
    protected $sidebarService;

    /**
     * Create a new component instance.
     *
     * @param array|null $menus Optional menus array.
     * @param SidebarService $sidebarService The navigation service.
     */
    public function __construct(SidebarService $sidebarService, ?array $menus = null) {
        $this->sidebarService = $sidebarService;

        // If $menus is provided, use it; otherwise, fetch from the service.
        // $this->menus = $sidebarService->getItems();
        $this->menus = $menus ?? $sidebarService->getItems();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.sidebar');
    }

}
