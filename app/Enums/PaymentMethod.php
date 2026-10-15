<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case GCash = 'gcash';
    case Maya = 'maya';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::GCash => 'GCash',
            self::Maya => 'Maya',
        };
    }

    /**
     * Digital wallets need a verified transaction reference instead of cash handling.
     */
    public function isDigital(): bool
    {
        return $this !== self::Cash;
    }
}
