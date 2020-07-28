<?php

namespace App\Service;

class EuroConverter implements EuroConverterInterface
{
    public function convert(array $rates, string $currency, float $amount): float
    {
        $convertRate = $rates[$currency] ?? 0;

        if (isset($rates[$currency])) {
            $amount = $amount / $convertRate;
        }

        return $amount;
    }
}
