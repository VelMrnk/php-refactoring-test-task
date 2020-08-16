<?php


namespace App\Fee\Calculator;


use App\Entity\TransactionInterface;
use App\Fee\CountryService;
use App\Service\EuropeCheckerInterface;

class FeeCalculatorRegion implements FeeCalculatorInterface
{

    /**
     * @var FeeCalculatorInterface
     */
    private FeeCalculatorInterface $next;
    private float $coefficient;
    /**
     * @var EuropeCheckerInterface
     */
    private EuropeCheckerInterface $europeChecker;
    /**
     * @var CountryService
     */
    private CountryService $countryService;

    public function __construct(
        FeeCalculatorInterface $next,
        float $coefficient = 0.01,
        EuropeCheckerInterface $europeChecker,
        CountryService $countryService
    ) {
        $this->next = $next;
        $this->coefficient = $coefficient;
        $this->europeChecker = $europeChecker;
        $this->countryService = $countryService;
    }

    /**
     * @param float $amount
     * @param TransactionInterface $transaction
     * @return float
     */
    public function calculate(float $amount, TransactionInterface $transaction): float
    {
        $country = $this->countryService->getCountryByBin($transaction->getBinCode());

        if($this->europeChecker->isEurope($country->code)) {
            return $this->next->calculate($amount, $transaction) * $this->coefficient;
        }

        return $this->next->calculate($amount, $transaction);
    }
}
