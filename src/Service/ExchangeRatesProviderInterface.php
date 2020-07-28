<?php

namespace App\Service;

interface ExchangeRatesProviderInterface
{
    public function getRates(): array;
}