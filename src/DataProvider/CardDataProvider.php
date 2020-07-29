<?php

namespace App\DataProvider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CardDataProvider implements CardDataProviderInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getCardInfo(string $url): array
    {
        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Card info was not found in the provided resource: '
                . $url
                . '.Please make sure the resource is available, bin code is correct and you have not overcome the limits.'
            );
        }

        return $response->toArray();
    }
}