<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\NavigationService\BreadcrumbService;

class Breadcrumb extends Component {
    public $breadcrumbs;
    /**
     * Create a new component instance.
     */
    public function __construct() {
        //
        $this->breadcrumbs = app(BreadcrumbService::class)->getItems();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.breadcrumb');
    }
}

