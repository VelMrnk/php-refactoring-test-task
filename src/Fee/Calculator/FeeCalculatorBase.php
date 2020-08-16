<?php


namespace App\Fee\Calculator;


use App\Entity\TransactionInterface;

class FeeCalculatorBase implements FeeCalculatorInterface
{
    public function calculate(float $amount, TransactionInterface $transaction): float
    {
        return $amount;
    }
}
