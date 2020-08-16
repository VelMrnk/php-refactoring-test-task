<?php


namespace App\Fee;


use App\Entity\Transaction;
use App\Fee\Calculator\FeeCalculatorInterface;
use App\Fee\Entity\Currency;

/**
 * Class TransactionHandler
 * @package App\Fee
 */
class TransactionHandler
{
    /**
     * @var CurrencyConverter
     */
    private CurrencyConverter $currencyConverter;
    /**
     * @var FeeCalculatorInterface
     */
    private FeeCalculatorInterface $fee;

    /**
     * TransactionHandler constructor.
     * @param CurrencyConverter $currencyConverter
     * @param FeeCalculatorInterface $fee
     */
    public function __construct(CurrencyConverter $currencyConverter, FeeCalculatorInterface $fee)
    {
        $this->currencyConverter = $currencyConverter;
        $this->fee = $fee;
    }

    /**
     * @param Transaction $transaction
     * @return float
     */
    public function handle(Transaction $transaction): float
    {
        $amountInEuro = $this->currencyConverter->convert(
            $transaction->getAmount(),
            new Currency($transaction->getCurrency()),
            new Currency('EUR')
        );

        return $this->fee->calculate($amountInEuro, $transaction);
    }
}
