<?php


namespace App\Fee\Entity;


class Rate
{
    public Currency $base;
    public Currency $target;
    public float $value;

    public function __construct(Currency $base, Currency $target, float $rate) {
        $this->base = $base;
        $this->target = $target;
        $this->value = $rate;
    }
}
