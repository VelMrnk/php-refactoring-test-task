<?php

namespace App\Tests;

use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    /**
     * @param string $binCode
     * @param float $amount
     * @param string $currency
     *
     * @dataProvider dataProvider
     */
    public function testTransactionEntity(string $binCode, float $amount, string $currency)
    {
        $transaction = new Transaction($binCode, $amount, $currency);

        $this->assertSame($binCode, $transaction->getBinCode());
        $this->assertSame($amount, $transaction->getAmount());
        $this->assertSame($currency, $transaction->getCurrency());
    }

    public function testSetters()
    {
        $testData = [
            'binCode' => '234445',
            'amount' => 400.0,
            'currency' => 'USD'
        ];

        $transaction = new Transaction('55674', 300, 'USD');
        $transaction->setBinCode($testData['binCode']);
        $transaction->setAmount($testData['amount']);
        $transaction->setCurrency($testData['currency']);

        $this->assertSame($testData['binCode'], $transaction->getBinCode());
        $this->assertSame($testData['amount'], $transaction->getAmount());
        $this->assertSame($testData['currency'], $transaction->getCurrency());
    }

    public function dataProvider(): array
    {
        return [
            ['45717360', 100, 'EUR'],
            ['516793', 50, 'USD'],
            ['45417360', 10000, 'JPY'],
            ['41417360', 130, 'USD'],
            ['4745030', 2000, 'GBP']
        ];
    }
}