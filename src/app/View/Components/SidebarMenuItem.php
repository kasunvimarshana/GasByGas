<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarMenuItem extends Component {
    public array $item;
    /**
     * Create a new component instance.
     */
    public function __construct(?array $item = []) {
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.sidebar-menu-item');
    }
}
