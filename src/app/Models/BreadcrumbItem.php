<?php
// declare(strict_types=1);
namespace App\Models;

use App\Models\NavigationItem;

class BreadcrumbItem extends NavigationItem {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_items';
}

