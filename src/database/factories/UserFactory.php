<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory {
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'status' => $this->faker->numberBetween(0, 1),
            // 'timezone' => $this->faker->timezone(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->userName(),
            'password' => static::$password ??= Hash::make(env('USER_DEFAULT_PASSWORD', 'password')),
            'description' => $this->faker->sentence(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            // 'image' => $this->faker->imageUrl(200, 200, 'people', true, 'Faker'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            // 'company_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // /**
    //  * Indicate that the user should belong to a specific company.
    //  */
    // public function forCompany(int $companyId): self {
    //     return $this->state(fn (array $attributes) => [
    //         'company_id' => $companyId,
    //     ]);
    // }
}
