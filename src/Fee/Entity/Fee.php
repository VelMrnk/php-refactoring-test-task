<?php


namespace App\Fee\Entity;


class Fee
{
    private float $amount;

    public function __construct(float $amount) {
        $this->amount = $amount;
    }
}
