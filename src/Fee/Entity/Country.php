<?php


namespace App\Fee\Entity;


class Country
{
    public string $code;

    public function __construct(string $code) {
        $this->code = $code;
    }
}
