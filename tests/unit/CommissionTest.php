<?php

namespace App\Tests;

use App\Entity\Commission;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    /**
     * @param float $transactionAmountInEuro
     * @param bool $isEurope
     * @param $expected
     *
     * @dataProvider dataProvider
     */
    public function testCalculateTransactionFee(float $transactionAmountInEuro, bool $isEurope, $expected)
    {
        $commission = new Commission();
        $commissionAmount = $commission->calculateTransactionFee($transactionAmountInEuro, $isEurope);
        $this->assertSame($expected, $commissionAmount);
    }

    public function dataProvider(): array
    {
        return [
            [100, true, 1.0],
            [42.67303917385, true, 0.4267303917385],
            [81.076698556835, false, 1.6215339711367],
            [110.94990185201, false, 2.2189980370402],
            [2198.5753231906, false, 43.971506463811]
        ];
    }
}