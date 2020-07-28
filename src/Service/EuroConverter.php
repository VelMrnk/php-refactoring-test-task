<?php

namespace App\Service;

class EuroConverter implements EuroConverterInterface
{
    public function convert(array $rates, string $currency, float $amount): float
    {
        if (isset($rates[$currency]) && $rates[$currency] !== null && $rates[$currency] > 0) {
            $amount = $amount / $rates[$currency];
        }

        return $amount;
    }
}
