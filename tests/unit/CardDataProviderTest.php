<?php

namespace App\Tests;

use App\DataProvider\CardDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CardDataProviderTest extends TestCase
{
    const SOURCE_URL = 'https://lookup.binlist.net/45717360';

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

        $cardDataProvider = new CardDataProvider($this->httpClientMock);
        $cardData = $cardDataProvider->getCardInfo(self::SOURCE_URL);

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

        $cardDataProvider = new CardDataProvider($this->httpClientMock);
        $cardDataProvider->getCardInfo(self::SOURCE_URL);
    }

    public function testGetCorrectResult()
    {
        $this->responseMock
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(200);

        $responseBody = '{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Visa/Dankort","prepaid":false,
            "country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},
            "bank":{"name":"Jyske Bank","url":"www.jyskebank.dk","phone":"+4589893300","city":"Hjørring"}}';

        $expectedResult = json_decode($responseBody, true);

        $this->responseMock
            ->method('toArray')
            ->willReturn($expectedResult);

        $this->httpClientMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->with($this->equalTo('GET'), $this->equalTo(self::SOURCE_URL))
            ->willReturn($this->responseMock);

        $cardDataProvider = new CardDataProvider($this->httpClientMock);
        $cardData = $cardDataProvider->getCardInfo(self::SOURCE_URL);

        $this->assertIsArray($cardData);
        $this->assertArrayHasKey('country', $cardData);
        $this->assertEquals($expectedResult, $cardData);
    }
}