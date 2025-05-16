<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH_ON_DELIVERY = 'cash_on_delivery';
    case VISA_CARD = 'visa_card';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
} 