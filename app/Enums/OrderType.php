<?php

namespace App\Enums;

enum OrderType: string
{
    case DineIn = 'dine_in';
    case TakeAway = 'take_away';
    case Delivery = 'delivery';

    public function label(): string
    {
        return match ($this) {
            self::DineIn => 'Dine In',
            self::TakeAway => 'Take Away',
            self::Delivery => 'Delivery',
        };
    }
}
