<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Config;
use App\Config\ConfigKeys;
use App\Entity\Transaction;
use App\Fee\Calculator\FeeCalculatorBase;
use App\Fee\Calculator\FeeCalculatorEurope;
use App\Fee\CountryService;
use App\Fee\CurrencyConverter;
use App\Fee\RateService;
use App\Fee\TransactionHandler;
use App\Service\JsonFileReader;
use App\Manager\TransactionFileManager;
use App\Controller\TransactionFileController;
use Symfony\Component\HttpClient\HttpClient;
use App\DataProvider\ExchangeRatesProvider;
use App\DataProvider\CardDataProvider;
use App\Service\EuroConverter;
use App\Service\EuropeChecker;

$input = [
    'filePath' => $argv[1]
];
//
//$httpClient = HttpClient::create();
//
//// App.php is kind of front-controller required to define dependencies in kernel
//$kernel = [
//    'jsonReader' => new JsonFileReader($input['filePath']),
//    'transactionFileManager' => new TransactionFileManager(),
//    'exchangeRatesProvider' => new ExchangeRatesProvider($httpClient),
//    'cardDataProvider' => new CardDataProvider($httpClient),
//    'euroConverter' => new EuroConverter(),
//    'europeChecker' => new EuropeChecker()
//];
//
//// Better to save config in file (yml/xml). I used Singleton in order to pass time limits. I know that it`s anti-pattern)
//$config = new App\Config\Config();
//$config->set('exchangeRatesProviderUrl', 'https://api.exchangeratesapi.io/latest');
//$config->set('cardDataProviderUrl', 'https://lookup.binlist.net');
//
//$transactionController = new TransactionFileController($kernel['jsonReader'], $kernel['transactionFileManager'], $config);
//$transactionController->showCommissions(
//    $kernel['exchangeRatesProvider'],
//    $kernel['cardDataProvider'],
//    $kernel['euroConverter'],
//    $kernel['europeChecker']
//);


$config = new Config();
$config->set(ConfigKeys::RATE_SERVICE_HOST, 'https://api.exchangeratesapi.io/latest');
$config->set(ConfigKeys::COUNTRY_SERVICE_HOST, 'https://lookup.binlist.net');
$config->set(ConfigKeys::FEE_EUROPE_COEFFICIENT, 0.01);
$config->set(ConfigKeys::FEE_WORLD_COEFFICIENT, 0.02);

$rateService = new RateService($config->get(ConfigKeys::RATE_SERVICE_HOST));
$countryService = new CountryService($config->get(ConfigKeys::COUNTRY_SERVICE_HOST));

$currencyConverter = new CurrencyConverter($rateService);

$feeCalculatorBase = new FeeCalculatorBase();

$europeChecker = new EuropeChecker();

$feeCalculatorEurope = new FeeCalculatorEurope(
    $feeCalculatorBase,
    $europeChecker,
    $countryService,
    $config->get(ConfigKeys::FEE_EUROPE_COEFFICIENT),
    $config->get(ConfigKeys::FEE_WORLD_COEFFICIENT),
);

$transactionHandler = new TransactionHandler($currencyConverter, $feeCalculatorEurope);

$jsonFileReader = new JsonFileReader($input['filePath']);

$lines = $jsonFileReader->toArray();

foreach ($lines as $line) {
    $transaction = new Transaction($line['bin'], $line['amount'], $line['currency']);
    $fee = $transactionHandler->handle($transaction);
    echo "{$fee}\n";
}
