<?php

namespace App\Enums;

enum OrderStatus: int {
    case PENDING = 0;
    case CANCELLED = 1;
    case COMPLETED = 2;
    // case APPROVED = 3;
    // case CONFIRMED = 4;
    // case REJECTED = 5;
    // case SUCCESS = 6;
    // case FAILED = 7;

    /**
     * Get all possible values of the enum.
     */
    public static function values(): array {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if a given value is valid.
     */
    public static function isValid(int $value): bool {
        return in_array($value, self::values(), true);
    }

    /**
     * Get a human-readable label for the enum.
     */
    public function label(): string {
        return match ($this) {
            self::PENDING => 'PENDING',
            self::CANCELLED => 'CANCELLED',
            self::COMPLETED => 'COMPLETED',
            // self::APPROVED => 'APPROVED',
            // self::CONFIRMED => 'CONFIRMED',
            // self::REJECTED => 'REJECTED',
            // self::SUCCESS => 'SUCCESS',
            // self::FAILED => 'FAILED',
        };
    }

    /**
     * Get a label for a specific value.
     */
    public static function labelFor(string $value): string {
        return match ($value) {
            self::PENDING->value => self::PENDING->label(),
            self::CANCELLED->value => self::CANCELLED->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
            // self::APPROVED->value => self::APPROVED->label(),
            // self::CONFIRMED->value => self::CONFIRMED->label(),
            // self::REJECTED->value => self::REJECTED->label(),
            // self::SUCCESS->value => self::SUCCESS->label(),
            // self::FAILED->value => self::FAILED->label(),
            default => 'Unknown',
        };
    }

    /**
     * Get an array representation of the enum for API responses.
     */
    public static function toArray(): array {
        return array_map(
            fn ($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
