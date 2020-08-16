<?php


namespace App\Fee\Calculator;

use App\Entity\TransactionInterface;

interface FeeCalculatorInterface
{
    public function calculate(float $amount, TransactionInterface $transaction): float;
}
