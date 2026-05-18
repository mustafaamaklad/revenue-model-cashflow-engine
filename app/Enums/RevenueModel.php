<?php

namespace App\Enums;

enum RevenueModel: string
{
    case Sale   = 'sale';
    case Rental = 'rental';
    case Hybrid = 'hybrid';

    public function hasSales(): bool
    {
        return $this === self::Sale || $this === self::Hybrid;
    }

    public function hasOperation(): bool
    {
        return $this === self::Rental || $this === self::Hybrid;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}