<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\NavigationItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NavigationItem>
 */
class NavigationItemFactory extends Factory {

    protected $model = NavigationItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            //
            'title' => $this->faker->sentence(3), // Generates a 3-word title
            'icon' => $this->faker->randomElement([
                'bi bi-clock-fill me-1',
                'bi bi-house me-1',
                'bi bi-gear me-1',
                'bi bi-people me-1',
                'bi bi-person me-1',
                'bi bi-chat me-1',
                'bi bi-bell me-1',
                'bi bi-bar-chart me-1',
                'bi bi-box me-1'
            ]), // Random Bootstrap Icon class
            'route' => null, // ($this->faker->optional()->slug() | $this->faker->unique()->slug) Random route or null
            'parent_id' => null, // You can manually handle parent-child in Seeder
            'order' => $this->faker->numberBetween(1, 100),
            'parameters' => json_encode(['param1' => $this->faker->word]), // Example JSON data
            'permission' => $this->faker->optional()->randomElement(['admin', 'editor', 'user']),
            'types' => json_encode($this->faker->randomElements(
                ['sidebar', 'main_navigation', 'breadcrumbs'],
                $this->faker->numberBetween(1, 3)
            )), // Example JSON data
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State for child navigation items.
     */
    public function child($parentId): Factory {
        return $this->state(fn () => [
            'parent_id' => $parentId,
        ]);
    }

    /**
     * Define a state for inactive navigation items.
     *
     * @return Factory
     */
    public function inactive(): Factory {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
