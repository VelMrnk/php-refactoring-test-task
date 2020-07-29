<?php

namespace App\DataProvider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesProvider implements ExchangeRatesProviderInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getRates(string $url): array
    {
        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            /**
             * Maybe here is better to create own Exception. But for the current test task we can use common Exception.
             * If we need separate Exception, please give me feedback about this.
             */
            throw new \Exception('Rates was not uploaded from resource: '
                . $url
                . "\n"
                . 'Please make sure the resource is available and correct and you have not overcome the limitations.'
            );
        }

        return $response->toArray();;
    }
}