<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;

class CompanySeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //
        // Create root companies
        $rootCompanies = Company::factory(10)->rootCompany()->create();

        // Create sub-companies, associating each with a random root company
        $rootCompanies->each(function ($rootCompany) {
            // Create sub-company for each root company
            Company::factory()->subCompany($rootCompany)->create();
        });

        // Create companies with associated users
        Company::factory(3)->withUsers(5)->create();  // Create 3 companies with 5 users each

        $this->command->info('Company seeded successfully!');
    }
}
