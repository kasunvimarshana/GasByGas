<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Exception;

trait UniqueIdGeneratable {
    /**
     * Cache duration in seconds (1 hour).
     */
    const CACHE_DURATION = 3600;

    /**
     * Default maximum number of attempts to generate a unique ID.
     */
    const MAX_ATTEMPTS = 100;

    /**
     * Generate a unique ID for a model column.
     *
     * @param string $column         The column to store the unique ID.
     * @param string $baseIdentifier The base identifier (e.g., the name or another property).
     * @param string|null $modelClass The class of the model for which the unique ID is being generated.
     * @param int $maxAttempts       Maximum number of attempts to generate a unique ID.
     * @return void
     * @throws Exception
     */
    public function generateUniqueId(string $column, string $baseIdentifier, ?string $modelClass = null, ?int $maxAttempts = null): void {
        // Use the provided maximum attempts or fall back to the default constant value
        $maxAttempts = $maxAttempts ?? self::MAX_ATTEMPTS;

        // Explicitly set model class if not provided.
        $modelClass = $modelClass ?? static::class; // (get_class($this);) Get class name explicitly if not passed.

        // Check if the column does not already have a value.
        if (!$this->hasValueInColumn($column)) {
            // Generate the base unique identifier (slugify the base identifier).
            $identifier = Str::slug($baseIdentifier);
            $originalIdentifier = $identifier;

            // Cache existing identifiers to avoid conflicts.
            $existingIdentifiers = $this->getExistingIdentifiers($modelClass, $column, $originalIdentifier);

            // If the generated identifier is not taken, use it.
            if (!$this->isIdentifierTaken($existingIdentifiers, $identifier)) {
                $this->setUniqueId($column, $identifier);
                return;
            }

            // Attempt to generate a unique identifier by appending a number.
            $this->attemptToGenerateUniqueId($column, $existingIdentifiers, $originalIdentifier, $maxAttempts);
        }
    }

    /**
     * Check if the column has a value.
     *
     * @param string $column
     * @return bool
     */
    protected function hasValueInColumn(string $column): bool {
        return !empty($this->{$column});
    }

    /**
     * Get existing identifiers from the database.
     *
     * @param string $modelClass
     * @param string $column
     * @param string $originalIdentifier
     * @return array
     */
    protected function getExistingIdentifiers(string $modelClass, string $column, string $originalIdentifier): array {
        return Cache::remember(
            "unique_ids_{$modelClass}_{$column}_{$originalIdentifier}",
            self::CACHE_DURATION,
            fn() => $modelClass::where($column, 'LIKE', "{$originalIdentifier}%")
                ->pluck($column)
                ->toArray()
        );
    }

    /**
     * Check if the identifier is already taken.
     *
     * @param array $existingIdentifiers
     * @param string $identifier
     * @return bool
     */
    protected function isIdentifierTaken(array $existingIdentifiers, string $identifier): bool {
        return in_array($identifier, $existingIdentifiers);
    }

    /**
     * Set the unique ID to the specified column.
     *
     * @param string $column
     * @param string $identifier
     * @return void
     */
    protected function setUniqueId(string $column, string $identifier): void {
        $this->{$column} = $identifier;
    }

    /**
     * Try appending numbers to the identifier to make it unique.
     *
     * @param string $column
     * @param array $existingIdentifiers
     * @param string $originalIdentifier
     * @param int $maxAttempts
     * @throws Exception
     * @return void
     */
    protected function attemptToGenerateUniqueId(string $column, array $existingIdentifiers, string $originalIdentifier, int $maxAttempts): void {
        for ($i = 1; $i <= $maxAttempts; $i++) {
            $newIdentifier = "{$originalIdentifier}_{$i}";
            if (!$this->isIdentifierTaken($existingIdentifiers, $newIdentifier)) {
                $this->setUniqueId($column, $newIdentifier);
                return;
            }
        }

        // If the identifier cannot be made unique after max attempts, throw an exception.
        throw new Exception(trans('validation.unique_id_generation_failed', ['attempts' => $maxAttempts]));
    }
}
