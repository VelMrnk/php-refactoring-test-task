<?php


namespace App\Fee\Calculator;


use App\Entity\TransactionInterface;
use App\Fee\CountryService;
use App\Service\EuropeCheckerInterface;

class FeeCalculatorEurope implements FeeCalculatorInterface
{

    /**
     * @var FeeCalculatorInterface
     */
    private FeeCalculatorInterface $next;
    /**
     * @var EuropeCheckerInterface
     */
    private EuropeCheckerInterface $europeChecker;
    /**
     * @var CountryService
     */
    private CountryService $countryService;
    private float $europeanCoefficient;
    private float $worldCoefficient;

    public function __construct(
        FeeCalculatorInterface $next,
        EuropeCheckerInterface $europeChecker,
        CountryService $countryService,
        float $europeanCoefficient = 0.01,
        float $worldCoefficient = 0.02
    ) {
        $this->next = $next;
        $this->europeChecker = $europeChecker;
        $this->countryService = $countryService;
        $this->europeanCoefficient = $europeanCoefficient;
        $this->worldCoefficient = $worldCoefficient;
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
            return $this->next->calculate($amount, $transaction) * $this->europeanCoefficient;
        }

        return $this->next->calculate($amount, $transaction) * $this->worldCoefficient;
    }
}
