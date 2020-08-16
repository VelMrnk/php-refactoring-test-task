<?php


namespace App\Fee;


use App\Fee\Entity\Currency;
use App\Fee\Entity\Rate;

class RateService
{
    private string $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function get(Currency $from, Currency $to): Rate {

        $rate = @json_decode(
            file_get_contents($this->host), true
        )['rates'][$from->code];

        if($rate == null) {
            throw new \RuntimeException("Rate from rest api is null");
        }

        return new Rate($from, $to, $rate);
    }
}
