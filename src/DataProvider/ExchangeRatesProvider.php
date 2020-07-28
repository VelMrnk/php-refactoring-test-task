<?php

namespace App\DataProvider;

use App\Service\ExchangeRatesProviderInterface;

class ExchangeRatesProvider implements ExchangeRatesProviderInterface
{
    private string $url;

    public function __construct(string $sourceUrl)
    {
        $this->url = $sourceUrl;
    }

    public function getRates(): array
    {
        $rates = @json_decode(file_get_contents($this->url), true);

        if ($rates == null) {
            /**
             * Maybe here is better to create own Exception. But for the current test task we can use common Exception.
             * If we need separate Exception, please give me feedback about this.
             */
            throw new \Exception('Rates was not uploaded from resource: '
                . $this->url
                . "\n"
                . 'Please make sure the resource is available and correct and you have not overcome the limitations.'
            );
        }

        return $rates;
    }
}