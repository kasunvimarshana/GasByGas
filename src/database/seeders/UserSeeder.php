<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //
        User::factory()->create([
            'id' => 1,
            'email' => 'kasunvmail@gmail.com',
        ]);

        User::factory()->count(30)->create();

        $this->command->info('User seeded successfully!');
    }
}

