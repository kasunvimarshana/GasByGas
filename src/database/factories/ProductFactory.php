<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            //
            'name' => $this->faker->words(3, true),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####-??')),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            // 'image' => $this->faker->imageUrl(200, 200, 'products', true, 'Faker'),
            'color' => $this->faker->safeColorName(),
            'description' => $this->faker->sentence(10),
        ];
    }
}
