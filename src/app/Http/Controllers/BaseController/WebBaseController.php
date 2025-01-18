<?php

namespace App\Http\Controllers\BaseController;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController\BaseController;

abstract class WebBaseController extends BaseController {
    /**
     * Render a view with optional data.
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function render(string $view, array $data = []) {
        return view($view, $data);
    }

    /**
     * Redirect to a specific route with an optional message.
     *
     * @param string $route
     * @param array $params
     * @param string|null $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToRoute(string $route, array $params = [], ?string $message = null) {
        if ($message) {
            session()->flash('message', $message);
        }

        return redirect()->route($route, $params);
    }
}
