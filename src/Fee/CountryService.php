<?php


namespace App\Fee;


use App\Fee\Entity\Country;

class CountryService implements CountryServiceInterface
{
    /**
     * @var string
     */
    private string $host;

    /**
     * CountryService constructor.
     * @param string $host
     */
    public function __construct(string $host) {

        $this->host = $host;
    }

    public function getCountryByBin(string $bin): Country {

        $binResults = file_get_contents("{$this->host}/{$bin}");
        if (!$binResults)
            throw new \RuntimeException("Cannot fetch countries");
        $r = json_decode($binResults);

        return new Country($r->country->alpha2);
    }
}
