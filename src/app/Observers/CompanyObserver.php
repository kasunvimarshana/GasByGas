<?php

namespace App\Observers;

// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Company;
use App\Services\UserService\UserServiceInterface;
use App\Services\UserService\UserService;
use App\Models\Product;

class CompanyObserver {
    protected UserServiceInterface $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function created(Company $company) {
        try {
            $data = [
                'name' => $company->name,
                'email' => $company->email,
                'password' => env('USER_DEFAULT_PASSWORD', 'password'),
                'description' => $company->description,
                'address' => $company->address,
                'phone' => $company->phone,
            ];

            // Create the user
            $user = $this->userService->create($data);

            // Attach the created user to the company in the pivot table
            $company->users()->attach($user->id, [
                // 'started_at' => now(),
            ]);

            foreach (Product::all() as $key => $value) {
                $company->stocks()->updateOrCreate([
                    'product_id' => $value->id,
                ], [
                    'quantity' => 0,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error creating user for company: ' . $e->getMessage());
        }
    }
}
