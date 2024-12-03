<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';

    public function color(): string
    {
        return match($this) {
            self::Paid => 'green',
            self::Pending => 'yellow'
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Paid => 'Paid',
            self::Pending => 'Pending'
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Paid => 'check',
            self::Pending => 'clock'
        };
    }
}
