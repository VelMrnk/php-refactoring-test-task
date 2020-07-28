<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use App\Service\JsonFileReader;
use App\Entity\Transaction;
use App\DataProvider\ExchangeRatesProvider;
use App\Service\EuroConverter;
use App\DataProvider\CardDataProvider;
use App\Service\EuropeChecker;
use App\Entity\Commission;
use App\Service\CeilingHelper;

$fileSystem = new Filesystem();
$filePath = $_SERVER['argv'][1];

if (!$fileSystem->exists($filePath)) {
    throw new FileNotFoundException(
        'File was not found. Please make sure file name/path are correct and the file exists.'
    );
}

$jsonReader = new JsonFileReader($filePath);
$transactions = $jsonReader->convertDataToArray();

if (count($transactions) === 0) {
    return null;
}

foreach ($transactions as $transaction)
{
    $transaction = new Transaction($transaction['bin'], $transaction['amount'], $transaction['currency']);
    $transactionAmount = $transaction->getAmount();

    if ($transaction->getCurrency() !== 'EUR') {
        $exchangeRates = new ExchangeRatesProvider('https://api.exchangeratesapi.io/latest');
        $rates = $exchangeRates->getRates()['rates'];

        $euroConverter = new EuroConverter();
        $transactionAmount = $euroConverter->convert($rates, $transaction->getCurrency(), $transactionAmount);
    }

    $cardDataProvider = new CardDataProvider('https://lookup.binlist.net');
    $cardData = json_decode($cardDataProvider->getInfoByBinCode($transaction->getBinCode()));

    if ($cardData === null) {
        // Here can be better exception, but i skip this in order to pass test time limitations
        throw new \Exception('Card info was not found in the provided resource: '
            . $this->url
            . "\n"
            . 'Please make sure the resource is available and correct and you have not overcome the limitations.'
        );
    }

    $europeChecker = new EuropeChecker();
    $isEurope = $europeChecker->isEurope($cardData->country->alpha2);

    $commission = new Commission();
    $commissionAmount = $commission->calculateTransactionFee($transactionAmount, $isEurope);
    $roundedFormat = (new CeilingHelper())->ceil($commissionAmount, 2);

    $result = "$roundedFormat\n";
    file_put_contents("php://output", $result);
}