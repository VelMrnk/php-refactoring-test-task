<?php

namespace App\Manager;

use App\DataProvider\CardDataProviderInterface;
use App\DataProvider\ExchangeRatesProviderInterface;
use App\Entity\Commission;
use App\Entity\Transaction;
use App\Service\CeilingHelper;
use App\Service\EuroConverterInterface;
use App\Service\EuropeCheckerInterface;

class TransactionFileManager implements TransactionManagerInterface
{
    public function getCommissions(
        ExchangeRatesProviderInterface $exchangeRatesProvider,
        CardDataProviderInterface $cardDataProvider,
        EuroConverterInterface $euroConverter,
        EuropeCheckerInterface $europeChecker,
        array $transactions,
        string $ratesProviderUrl,
        string $cardInfoProviderUrl
    ): array
    {
        $commissions = [];

        foreach ($transactions as $transaction)
        {
            $transaction = new Transaction($transaction['bin'], $transaction['amount'], $transaction['currency']);
            $transactionAmount = $transaction->getAmount();

            if ($transaction->getCurrency() !== 'EUR') {
                $rates = $exchangeRatesProvider->getRates($ratesProviderUrl)['rates'];
                $transactionAmount = $euroConverter->convert($rates, $transaction->getCurrency(), $transactionAmount);
            }

            $cardData = $cardDataProvider->getCardInfo($cardInfoProviderUrl . '/' . $transaction->getBinCode());
            $isEurope = $europeChecker->isEurope($cardData['country']['alpha2']);

            $commission = new Commission();
            $commissionAmount = $commission->calculateTransactionFee($transactionAmount, $isEurope);

            $commissions[] = (new CeilingHelper())->ceil($commissionAmount, 2);
        }

        return $commissions;
    }
}