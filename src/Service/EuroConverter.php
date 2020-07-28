<?php

namespace App\Service;

class EuroConverter
{
    public function convert($rates, string $currency, float $totalAmount): float
    {
        $currentRate = $rates['rates'][$currency] ?? 0;

        if (isset($rates['rates'][$currency])) {
            $totalAmount = $totalAmount / $currentRate;
        }

        return $totalAmount;
    }
}
