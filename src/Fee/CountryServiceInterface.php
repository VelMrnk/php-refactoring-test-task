<?php


namespace App\Fee;


use App\Fee\Entity\Country;

interface CountryServiceInterface
{
    public function getCountryByBin(string $bin): Country;
}
