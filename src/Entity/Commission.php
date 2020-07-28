<?php

namespace App\Entity;

/**
 * Possible that here must be dependency from transaction object if this is database entity.
 * But currently transaction here is not needed.
 */
class Commission
{
    const EUROPE_FEE = 0.01;

    const WORLD_FEE = 0.02;

    /**
     * @param float $transactionAmountInEuro - must be in euro already and comes from EuroConverter
     * @param bool $isEurope - comes form EuropeChecker
     * @return float
     */
    public function calculateTransactionFee(float $transactionAmountInEuro, bool $isEurope): float
    {
        return $transactionAmountInEuro * ($isEurope ? self::EUROPE_FEE : Commission::WORLD_FEE);
    }
}