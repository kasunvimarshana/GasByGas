<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Message extends Component {
    public string $type;
    public string $message;
    public bool $dismissible;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type = 'info', string $message = '', bool $dismissible = false) {
        $this->type = $type;
        $this->message = $message;
        $this->dismissible = $dismissible;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.message');
    }
}
