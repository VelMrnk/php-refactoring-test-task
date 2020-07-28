<?php

namespace App\Service;

use App\DataProvider\EuropeCountriesProvider;

class EuropeChecker implements EuropeCheckerInterface
{
    public function isEurope(string $countryCode): bool
    {
        return in_array($countryCode, EuropeCountriesProvider::COUNTRY_CODES, true);
    }
}
