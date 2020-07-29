<?php

namespace App\Tests;

use App\DataProvider\ExchangeRatesProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRatesProviderTest extends TestCase
{
    const SOURCE_URL = 'https://api.exchangeratesapi.io/latest';

    private HttpClientInterface $httpClientMock;

    private MockResponse $responseMock;

    public function setUp(): void
    {
        $this->httpClientMock = $this->createMock(MockHttpClient::class);
        $this->responseMock = $this->createMock(MockResponse::class);
    }

    public function testGetInfoSuccess()
    {
        $this->responseMock
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->httpClientMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->with($this->equalTo('GET'), $this->equalTo(self::SOURCE_URL))
            ->willReturn($this->responseMock);

        $cardDataProvider = new ExchangeRatesProvider($this->httpClientMock);
        $cardData = $cardDataProvider->getRates(self::SOURCE_URL);

        $this->assertIsArray($cardData);
    }

    public function testException()
    {
        $this->expectException(\Exception::class);

        $this->responseMock
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(404);

        $this->httpClientMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->with($this->equalTo('GET'), $this->equalTo(self::SOURCE_URL))
            ->willReturn($this->responseMock);

        $cardDataProvider = new ExchangeRatesProvider($this->httpClientMock);
        $cardDataProvider->getRates(self::SOURCE_URL);
    }

    public function testGetCorrectResult()
    {
        $this->responseMock
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(200);

        $responseBody = '{
            "rates":
            {
                "CAD":1.5693,"HKD":9.081,"ISK":158.6,"PHP":57.664,"DKK":7.4429,"HUF":346.98,"CZK":26.251,"AUD":1.6412,
                "RON":4.8355,"SEK":10.282,"IDR":17030.66,"INR":87.714,"BRL":6.065,"RUB":84.8413,"HRK":7.507,"JPY":123.34,
                "THB":36.92,"CHF":1.0758,"SGD":1.6168,"PLN":4.4054,"BGN":1.9558,"TRY":8.1283,"CNY":8.2041,"NOK":10.694,
                "NZD":1.763,"ZAR":19.4315,"USD":1.1717,"MXN":25.8438,"ILS":4.0021,"GBP":0.90968,"KRW":1406.13,"MYR":4.9827
            },
            "base":"EUR","date":"2020-07-28"}';

        $expectedResult = json_decode($responseBody, true);

        $this->responseMock
            ->method('toArray')
            ->willReturn($expectedResult);

        $this->httpClientMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->with($this->equalTo('GET'), $this->equalTo(self::SOURCE_URL))
            ->willReturn($this->responseMock);

        $cardDataProvider = new ExchangeRatesProvider($this->httpClientMock);
        $cardData = $cardDataProvider->getRates(self::SOURCE_URL);

        $this->assertIsArray($cardData);
        $this->assertArrayHasKey('rates', $cardData);
        $this->assertEquals($expectedResult, $cardData);
    }
}