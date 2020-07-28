<?php

namespace App\DataProvider;

interface CardDataProviderInterface
{
   public function getCardInfo(string $url): array;
}