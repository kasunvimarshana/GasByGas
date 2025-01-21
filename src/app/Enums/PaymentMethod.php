<?php

namespace App\Enums;

enum PaymentMethod: string {
    case COD = 'COD';

    /**
     * Get all possible values of the enum.
     */
    public static function values(): array {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if a given value is valid.
     */
    public static function isValid(string $value): bool {
        return in_array($value, self::values(), true);
    }

    /**
     * Get a human-readable label for the enum.
     */
    public function label(): string {
        return match ($this) {
            self::COD => 'Cash on delivery',
        };
    }
}
