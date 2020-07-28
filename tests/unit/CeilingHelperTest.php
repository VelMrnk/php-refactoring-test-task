<?php
/**
namespace App\Tests;

use App\Service\CeilingHelper;
use PHPUnit\Framework\TestCase;

class CeilingHelperTest extends TestCase
{

     * @dataProvider dataProvider
     * @param float $before
     * @param float $expected

    public function testCeil(float $before, float $expected)
    {
        $ceilingHelper = new CeilingHelper();
        $this->assertSame( $ceilingHelper->ceil($before, 2), $expected);
    }

    public function dataProvider(): array
    {
        return [
            [0.46180, 0.47],
            [0.42112, 0.43],
            [0.48190, 0.49],
            [0.4900987, 0.5],
            [0.18129, 0.19]
        ];
    }
}  */