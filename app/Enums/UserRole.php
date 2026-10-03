<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Cashier = 'cashier';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Cashier => 'Cashier',
        };
    }

    /**
     * Name of the route a user with this role lands on after logging in.
     */
    public function homeRoute(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Cashier => 'dashboard',
        };
    }
}
