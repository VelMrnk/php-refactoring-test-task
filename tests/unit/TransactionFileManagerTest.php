<?php

namespace App\Tests;

use App\DataProvider\CardDataProvider;
use App\DataProvider\CardDataProviderInterface;
use App\DataProvider\ExchangeRatesProvider;
use App\DataProvider\ExchangeRatesProviderInterface;
use App\Manager\TransactionFileManager;
use App\Service\EuroConverter;
use App\Service\EuroConverterInterface;
use App\Service\EuropeChecker;
use App\Service\EuropeCheckerInterface;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TransactionFileManagerTest extends TestCase
{
    private HttpClientInterface $httpClientMock;

    private MockResponse $responseMock;

    private ExchangeRatesProviderInterface $exchangeRatesProvider;

    private EuroConverterInterface $euroConverter;

    private EuropeCheckerInterface $europeChecker;

    private array $transactions;

    private string $ratesProviderUrl;

    private string $cardInfoProviderUrl;

    public function setUp(): void
    {
        $this->httpClientMock = $this->createMock(MockHttpClient::class);
        $this->exchangeRatesProvider = $this->createMock(ExchangeRatesProvider::class);
        $this->euroConverter = $this->createMock(EuroConverter::class);
        $this->europeChecker = $this->createMock(EuropeChecker::class);

        $this->responseMock = $this->createMock(MockResponse::class);
        $this->responseMock
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(200);

        $this->transactions = [
            [
                'bin' => '323244',
                'amount' => 100,
                'currency' => 'EUR'
            ]
        ];

        $this->ratesProviderUrl = 'https://api.exchangeratesapi.io/latest';
        $this->cardInfoProviderUrl = 'https://lookup.binlist.net';
    }

    public function testCommissions()
    {
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
            ->with($this->equalTo('GET'), $this->equalTo($this->cardInfoProviderUrl . '/323244'))
            ->willReturn($this->responseMock);

        $cardDataProvider = new CardDataProvider($this->httpClientMock);

        $transactionFileManager = new TransactionFileManager();

        $commissions = $transactionFileManager->getCommissions(
            $this->exchangeRatesProvider,
            $cardDataProvider,
            $this->euroConverter,
            $this->europeChecker,
            $this->transactions,
            $this->ratesProviderUrl,
            $this->cardInfoProviderUrl
        );

        $this->assertIsArray($commissions);
    }
}