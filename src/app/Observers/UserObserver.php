<?php

namespace App\Observers;

// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class UserObserver {

    /**
     * Handle the User "saving" event.
     *
     * @param \App\Models\User $model
     * @return void
     */
    public function saving(User $model): void {
        // $model->forceFill([]);
        if (empty($model->username)) {
            // Generate a slug from the user's name (lowercase, hyphenated)
            $nameSlug = Str::slug($model->short_name ?? $model->full_name);

            // Retrieve the maximum user ID from the database
            // Default to 0 if no users exist
            $lastUserId = User::max('id') ?? 0;

            // Generate the new username, combining the slug and padded ID
            $generatedUsername = $this->generateUsername($nameSlug, $lastUserId);

            // Set the generated username to the model
            $model->username = $generatedUsername;
        }
    }

    /**
     * Generate a username based on the user's name slug and the last user ID.
     *
     * @param string $nameSlug
     * @param int $lastUserId
     * @return string
     */
    private function generateUsername(string $nameSlug, int $lastUserId): string {
        // Increment the last user ID to create a new unique ID
        $newUserId = $lastUserId + 1;

        // Format the new username with padding for a consistent format
        return 'User_' . $nameSlug . str_pad($newUserId, 5, '0', STR_PAD_LEFT);
    }

}
