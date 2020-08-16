<?php


namespace App\Fee\Entity;


class Currency
{
    public string $code;

    public function __construct(string $code) {
        $this->code = $code;
    }

    public function equals(Currency $currency) {
        return $this->code === $currency->code;
    }
}
