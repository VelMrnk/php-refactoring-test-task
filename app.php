<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\JsonFileReader;
use App\Entity\Transaction;
use Symfony\Component\HttpClient\HttpClient;
use App\DataProvider\ExchangeRatesProvider;
use App\Service\EuroConverter;
use App\DataProvider\CardDataProvider;
use App\Service\EuropeChecker;
use App\Entity\Commission;
use App\Service\CeilingHelper;

$filePath = $_SERVER['argv'][1];

$jsonReader = new JsonFileReader($filePath);
$transactions = $jsonReader->toArray();

if (count($transactions) === 0) {
    return null;
}

$httpClient = HttpClient::create();

foreach ($transactions as $transaction)
{
    $transaction = new Transaction($transaction['bin'], $transaction['amount'], $transaction['currency']);
    $transactionAmount = $transaction->getAmount();

    if ($transaction->getCurrency() !== 'EUR') {
        $exchangeRates = new ExchangeRatesProvider($httpClient);
        $rates = $exchangeRates->getRates('https://api.exchangeratesapi.io/latest')['rates'];

        $euroConverter = new EuroConverter();
        $transactionAmount = $euroConverter->convert($rates, $transaction->getCurrency(), $transactionAmount);
    }

    $cardDataProvider = new CardDataProvider($httpClient);
    $cardData = $cardDataProvider->getCardInfo('https://lookup.binlist.net/' . $transaction->getBinCode());

    if ($cardData === null) {
        // Here can be better exception, but i skip it in order to pass test time limitations
        throw new \Exception('Please make sure the resource is available and you have not overcome the limitations.');
    }

    $europeChecker = new EuropeChecker();
    $isEurope = $europeChecker->isEurope($cardData['country']['alpha2']);

    $commission = new Commission();
    $commissionAmount = $commission->calculateTransactionFee($transactionAmount, $isEurope);

    $roundedFormat = (new CeilingHelper())->ceil($commissionAmount, 2);

    $result = "$roundedFormat\n";
    file_put_contents("php://output", $result);
}