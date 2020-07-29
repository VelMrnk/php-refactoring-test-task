<?php

namespace App\Service;

interface CeilingHelperInterface
{
    public function ceil(float $amount, int $precisions): float;
}