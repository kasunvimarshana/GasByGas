<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory {
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            //
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'description' => $this->faker->sentence(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            // 'image' => $this->faker->imageUrl(200, 200, 'business', true, 'Faker'),
            'type' => $this->faker->randomElement([0, 1]),
            'is_active' => $this->faker->boolean(80),
            // 'parent_id' => $this->faker->optional()->randomElement(Company::pluck('id')->toArray()),
            'parent_id' => null,
        ];
    }

    /**
     * Create a root company (without a parent).
     */
    public function rootCompany(): self {
        return $this->state([
            'type' => 0, // root company
            'parent_id' => null,
        ]);
    }

    /**
     * Create a sub-company (with a parent).
     */
    public function subCompany(Company $parent): self {
        return $this->state([
            'type' => 1, // sub company
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Create a company with associated users.
     */
    public function withUsers(int $userCount = 3): self {
        return $this->afterCreating(function (Company $company) use ($userCount) {
            $users = User::factory($userCount)->create(); // Create users
            $company->users()->attach($users); // Attach users to the company
        });
    }
}
