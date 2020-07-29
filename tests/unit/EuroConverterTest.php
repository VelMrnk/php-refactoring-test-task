<?php

namespace App\Tests;

use App\Service\EuroConverter;
use PHPUnit\Framework\TestCase;

class EuroConverterTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param string $currency
     * @param float $amount
     * @param $expected
    */
    public function testConvert(string $currency, float $amount, $expected)
    {
        $euroConverter = new EuroConverter();
        $this->assertSame($euroConverter->convert($this->getRates(), $currency, $amount), $expected);
    }

    public function dataProvider(): array
    {
        return [
            ['USD', 50, 42.517006802721],
            ['JPY', 10000, 80.723280594123],
            ['USD', 130, 110.54421768707],
            ['GBP', 2000, 2189.6211955332]
        ];
    }

    private function getRates()
    {
        return [
            "CAD" => 1.5729,
            "HKD" => 9.1154,
            "ISK" => 158.4,
            "PHP" => 57.817,
            "DKK" => 7.4436,
            "HUF" => 345.91,
            "CZK" => 26.193,
            "AUD" => 1.6472,
            "RON" => 4.8303,
            "SEK" => 10.2678,
            "IDR" => 17075.52,
            "INR" => 87.885,
            "BRL" => 6.0919,
            "RUB" => 84.0682,
            "HRK" => 7.5163,
            "JPY" => 123.88,
            "THB" => 37.05,
            "CHF" => 1.0838,
            "SGD" => 1.6216,
            "PLN" => 4.4016,
            "BGN" => 1.9558,
            "TRY" => 8.0552,
            "CNY" => 8.2344,
            "NOK" => 10.6683,
            "NZD" => 1.7605,
            "ZAR" => 19.338,
            "USD" => 1.176,
            "MXN" => 26.0083,
            "ILS" => 4.0143,
            "GBP" => 0.9134,
            "KRW" => 1407.15,
            "MYR" => 5.0009,
        ];
    }
}