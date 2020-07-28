<?php

namespace App\Tests;

use App\DataProvider\CardDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CardDataProviderTest extends TestCase
{
    const SOURCE_URL = 'https://lookup.binlist.net/45717360';

    const TEST_COUNTRY_CODE = 'DK';

    private MockHttpClient $httpClientMock;

    private CardDataProvider $cardDataProvider;

    public function setUp(): void
    {
        $this->httpClientMock = $this->createMock(MockHttpClient::class);
        $this->cardDataProvider = new CardDataProvider($this->httpClientMock);
    }


    public function testGetInfoSuccess()
    {
        $testResponseBody = '{"number":{"length":16,"luhn":true},"scheme":"visa","type":"debit","brand":"Visa/Dankort","prepaid":false,"country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank","url":"www.jyskebank.dk","phone":"+4589893300","city":"Hjørring"}}';
        $responseMock = $this->createMock(MockResponse::class);

        $this->httpClientMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->with($this->equalTo('GET'), $this->equalTo(self::SOURCE_URL))
            ->willReturn($responseMock);

        $responseMock
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(200);

        $cardData = $this->cardDataProvider->getCardInfo(self::SOURCE_URL);

        $expectedResult = $testResponseBody;

        $this->assertTrue(is_string($cardData));
        $this->assertEquals($testResponseBody, $cardData);

       // $cardData = $this->cardDataProvider->getCardInfo(self::SOURCE_URL);

    //    $this->assertIsArray($cardData);
      //  $this->assertArrayHasKey($cardData['country']);
    }

    /**
     * @expectedException

    public function testException()
    {
        $this->expectException(\Exception::class);

        $cardDataProvider = new CardDataProvider($this->httpClient,'https://anybadsource1234.com', self::TEST_BIN);
    }*/
}