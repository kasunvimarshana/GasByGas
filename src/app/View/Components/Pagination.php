<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination extends Component {
    public $paginator;
    public $view;
    public $alignment;
    public $pageLimit;

    /**
     * Create a new component instance.
     *
     * @param  \Illuminate\Pagination\LengthAwarePaginator  $paginator
     * @param  string  $view
     * @param  int  $pageLimit
     * @param  string  $alignment
     * @return void
     */
    public function __construct(LengthAwarePaginator $paginator, string $view = 'vendor.pagination.bootstrap-4', int $pageLimit = 10, string $alignment = 'center') {
        $this->paginator = $paginator;
        $this->view = $view;
        $this->pageLimit = $pageLimit;
        $this->alignment = $alignment;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pagination');
    }
}

