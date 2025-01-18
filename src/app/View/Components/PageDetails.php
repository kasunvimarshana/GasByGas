<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageDetails extends Component {
    public array $details;
    /**
     * Create a new component instance.
     *
     * @param array $details
     */
    public function __construct(array $details = []) {
        $this->details = $details;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.page-details');
    }
}
