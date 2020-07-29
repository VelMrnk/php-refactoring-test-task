<?php

namespace App\DataProvider;

interface ExchangeRatesProviderInterface
{
    public function getRates(): array;
}