<?php

namespace App\Services\NavigationService;

use App\Services\NavigationService\BaseNavigationService;
use App\Models\SidebarItem;

class SidebarService extends BaseNavigationService {
    protected string $modelClass = SidebarItem::class;

    public function __construct(string $type = '') {
        parent::__construct($type);
    }
}

