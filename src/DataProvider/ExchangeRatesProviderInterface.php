<?php

namespace App\DataProvider;

interface ExchangeRatesProviderInterface
{
    public function getRates(string $url): array;
}