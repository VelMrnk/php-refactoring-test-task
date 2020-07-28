<?php

namespace App\Service;

interface EuroConverterInterface
{
    public function convert(array $rates, string $currency, float $amount): float;
}