<?php

namespace App\Entity;

interface TransactionInterface
{
    public function setBinCode(string $binCode);
    public function getBinCode(): string;

    public function setAmount(float $amount);
    public function getAmount(): float;

    public function setCurrency(string $currency);
    public function getCurrency(): string;
}