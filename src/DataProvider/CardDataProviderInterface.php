<?php

namespace App\DataProvider;

interface CardDataProviderInterface
{
   public function getInfoByBinCode(string $binCode): string ;
}