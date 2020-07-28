<?php

namespace App\Service;

interface EuropeCheckerInterface
{
    public function isEurope(string $countryCode): bool;
}