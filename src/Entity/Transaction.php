<?php

namespace App\Entity;

class Transaction
{
    private string $binCode;

    private float $amount;

    private string $currency;

    public function __construct(string $binCode, float $amount, string $currency)
    {
        $this->binCode = $binCode;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getBinCode(): string
    {
        return $this->binCode;
    }

    public function setBinCode(string $binCode): void
    {
        $this->binCode = $binCode;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}