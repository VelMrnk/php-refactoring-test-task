<?php

namespace App\Manager;

use App\DataProvider\CardDataProviderInterface;
use App\DataProvider\ExchangeRatesProviderInterface;
use App\Service\EuroConverterInterface;
use App\Service\EuropeCheckerInterface;

interface TransactionManagerInterface
{
    public function getCommissions(
        ExchangeRatesProviderInterface $exchangeRatesProvider,
        CardDataProviderInterface $cardDataProvider,
        EuroConverterInterface $euroConverter,
        EuropeCheckerInterface $europeChecker,
        array $transactions,
        string $ratesProviderUrl,
        string $cardInfoProviderUrl
    ): array;
}
