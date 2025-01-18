<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\NavigationItem;

class NavigationItemSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //
        /*
        $parent = NavigationItem::factory()->create([
            'title' => 'Dashboard',
        ]);

        NavigationItem::factory()->count(3)->child($parent->id)->create([
            'title' => 'Submenu Item',
        ]);
        */
        $navigationItems = $this->getNavigationItems();

        $this->createNavigationItems($navigationItems);

        $this->command->info('NavigationItem seeded successfully!');
    }

    private function getNavigationItems(): array {
        $navigationItems = config('navigation.items', []);
        return $navigationItems;
    }

    private function createNavigationItems(array $navigationItems): void {
        foreach ($navigationItems as $key => $navigationItem) {
            $this->command->info('Parent : ' . $key);
            $this->saveNavigationItem($navigationItem, null);
        }
    }

    private function saveNavigationItem(array $navigationItem, ?int $parentId = null): void {
        // Prepare the navigation item data
        $data = [
            'title' => $navigationItem['title'],
            'icon' => $navigationItem['icon'],
            'route' => $navigationItem['route'],
            'parent_id' => $parentId,
            'order' => $navigationItem['order'],
            'parameters' => $navigationItem['parameters'],
            'permission' => $navigationItem['permission'],
            'types' => $navigationItem['types'],
            'is_active' => $navigationItem['is_active'],
        ];

        // Insert the navigation item into the database
        // $navigationItem = DB::table('navigation_items')->insertGetId($data);
        $navigationItemInstance = NavigationItem::firstOrCreate($data);

        // Insert children recursively if any
        if (isset($navigationItem['children'])) {
            foreach ($navigationItem['children'] as $childKey => $child) {
                $this->command->info('Child : ' . $childKey);
                $this->saveNavigationItem($child, $navigationItemInstance->id);
            }
        }
    }
}
