<?php

namespace App\Service;

class CeilingHelper implements CeilingHelperInterface
{
    public function ceil(float $amount, int $precision): float
    {
        $pow = pow(10, $precision);
        return (ceil( $pow * $amount) + ceil($pow * $amount - ceil($pow * $amount))) / $pow;
    }
}
