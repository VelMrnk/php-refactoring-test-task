<?php

namespace App\Controller;

use App\Config\Config;
use App\DataProvider\CardDataProviderInterface;
use App\DataProvider\ExchangeRatesProviderInterface;
use App\Manager\TransactionManagerInterface;
use App\Service\EuroConverterInterface;
use App\Service\EuropeCheckerInterface;
use App\Service\JsonFileReaderInterface;

class TransactionFileController
{
    private JsonFileReaderInterface $jsonFileReader;

    private TransactionManagerInterface $transactionManager;

    private Config $config;

    public function __construct(JsonFileReaderInterface $jsonFileReader, TransactionManagerInterface $transactionManager, Config $config)
    {
        $this->jsonFileReader = $jsonFileReader;
        $this->transactionManager = $transactionManager;
        $this->config = $config;
    }

    public function showCommissions(
        ExchangeRatesProviderInterface $exchangeRatesProvider,
        CardDataProviderInterface $cardDataProvider,
        EuroConverterInterface $euroConverter,
        EuropeCheckerInterface $europeChecker
    ) {
        $transactions = $this->jsonFileReader->toArray();

        // Here can be DTO, but i skip it in order to pass time limits
        $commissions = $this->transactionManager->getCommissions(
            $exchangeRatesProvider,
            $cardDataProvider,
            $euroConverter,
            $europeChecker,
            $transactions,
            $this->config->get('exchangeRatesProviderUrl'),
            $this->config->get('cardDataProviderUrl')
        );

        foreach ($commissions as $commission) {
            $result = "$commission\n";
            file_put_contents("php://output", $result);
        }
    }
}
