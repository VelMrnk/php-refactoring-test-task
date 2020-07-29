<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Config;
use App\Service\JsonFileReader;
use App\Manager\TransactionFileManager;
use App\Controller\TransactionFileController;
use Symfony\Component\HttpClient\HttpClient;
use App\DataProvider\ExchangeRatesProvider;
use App\DataProvider\CardDataProvider;
use App\Service\EuroConverter;
use App\Service\EuropeChecker;

$input = [
    'filePath' => $_SERVER['argv'][1]
];

$httpClient = HttpClient::create();

// App.php is kind of front-controller required to define dependencies in kernel
$kernel = [
    'jsonReader' => new JsonFileReader($input['filePath']),
    'transactionFileManager' => new TransactionFileManager(),
    'exchangeRatesProvider' => new ExchangeRatesProvider($httpClient),
    'cardDataProvider' => new CardDataProvider($httpClient),
    'euroConverter' => new EuroConverter(),
    'europeChecker' => new EuropeChecker()
];

// Better to save config in file (yml/xml). I used Singleton in order to pass time limits. I know that it`s anti-pattern)
$config = Config::getInstance();
$config->setProperty('exchangeRatesProviderUrl', 'https://api.exchangeratesapi.io/latest');
$config->setProperty('cardDataProviderUrl', 'https://lookup.binlist.net');

$transactionController = new TransactionFileController($kernel['jsonReader'], $kernel['transactionFileManager'], $config);
$transactionController->showCommissions(
    $kernel['exchangeRatesProvider'],
    $kernel['cardDataProvider'],
    $kernel['euroConverter'],
    $kernel['europeChecker']
);