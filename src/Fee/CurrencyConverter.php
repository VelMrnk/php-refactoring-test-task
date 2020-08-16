<?php


namespace App\Fee;


use App\Fee\Entity\Currency;

class CurrencyConverter
{
    private RateService $rateService;

    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    public function convert(float $amount, Currency $base, Currency $to): float
    {
        return ($base->equals($to) || ($rate = $this->rateService->get($base, $to))->value == 0)
            ? $amount
            : $amount / $rate->value;
    }
}
