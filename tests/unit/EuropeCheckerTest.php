<?php

namespace App\Tests;

use App\Service\EuropeChecker;
use PHPUnit\Framework\TestCase;

class EuropeCheckerTest extends TestCase
{
    /**
     * @param string $countryCode
     * @param bool $result
     *
     * @dataProvider dataProvider
     */
    public function testIsEurope(string $countryCode, bool $result)
    {
        $europeChecker = new EuropeChecker();
        $this->assertSame($europeChecker->isEurope($countryCode), $result);
    }

    public function dataProvider(): array
    {
        return [
            ['AT', true],
            ['BE', true],
            ['ES', true],
            ['LU', true],
            ['NL', true],
            ['AG', false],
            ['CA', false],
            ['US', false],
            ['IN', false],
            ['AS', false]
        ];
    }
}