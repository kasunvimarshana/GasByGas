<?php

namespace App\Enums;

enum StockMovementType: string {
    case IN = 'IN';
    case OUT = 'OUT';

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
            self::IN => 'Stock In',
            self::OUT => 'Stock Out',
        };
    }

    /**
     * Get a label for a specific value.
     */
    public static function labelFor(string $value): string {
        return match ($value) {
            self::IN->value => self::IN->label(),
            self::OUT->value => self::OUT->label(),
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
