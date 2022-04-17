<?php

namespace Tests\Unit\Lib;

use \PHPUnit\Framework\TestCase;
use ComissionCli\Exception\HttpNotFoundException;
use ComissionCli\Lib\ApiRequest;
use ComissionCli\Lib\ComissionHelper;
use Exception;

class ApiRequestTest extends TestCase
{
    private array $binProviderResult;
    private string $binRequestUrlwithNum;
    private array $failedExchangeRatesResult;

    public function setUp(): void
    {
        parent::setUp();
        $this->binProviderResult = [
            'number' => [],
            'scheme' => 'mastercard',
            'type' => 'debit',
            'brand' => 'Debit',
            'country' => [
                'numeric' => '440',
                'alpha2' => 'LT',
                'name' => 'Lithuania',
                'emoji' => '🇱🇹',
                'currency' => 'EUR',
                'latitude' => 56,
                'longitude' => 24,
            ],
            'bank'=> [],
        ];
        $this->binRequestUrlwithNum = sprintf('%s/%s', ComissionHelper::DEFAULT_BIN_PROVIDER_URL, '516793');
        $this->failedExchangeRatesResult = [
            'success' => false,
            'error' => [
                'code' => 101,
                'type' => 'missing_access_key',
                'info' => 'You have not supplied an API Access Key. [Required format: access_key=YOUR_ACCESS_KEY]',
            ]
        ];
    }

    public function testSuccessfulApiRequestForCountryCodeByBinProvider(): void
    {
        $apiRequest = $this->createMock(ApiRequest::class);
        $apiRequest->expects(self::once())
                   ->method('getJson')
                   ->with($this->binRequestUrlwithNum)
                   ->willReturn($this->binProviderResult);
        $binJson = $apiRequest->getJson($this->binRequestUrlwithNum);
        self::assertEquals('LT', $binJson['country']['alpha2']);
    }

    public function testFailedApiRequestForRateByExchangeRatesProvider(): void
    {
        $apiRequest = $this->createMock(ApiRequest::class);
        $apiRequest->expects(self::once())
                   ->method('getJson')
                   ->with(ComissionHelper::DEFAULT_CURRENCY_RATES_PROVIDER_URL)
                   ->willReturn($this->failedExchangeRatesResult);
        $ratesJson = $apiRequest->getJson(ComissionHelper::DEFAULT_CURRENCY_RATES_PROVIDER_URL);
        self::assertEquals(101, $ratesJson['error']['code']);
    }

    public function testFailedApiRequestWithHttpNotFoundException(): void
    {
        $this->expectException(HttpNotFoundException::class);
        $badUrl = 'bad_test_url';
        $apiRequest = $this->createMock(ApiRequest::class);
        $apiRequest->expects(self::once())
                   ->method('getRawData')
                   ->with($badUrl)
                   ->willReturn(false);
        $apiRequest->expects(self::once())
                   ->method('getJson')
                   ->with($badUrl)
                   ->will($this->throwException(new HttpNotFoundException()));
        $apiRequest->getRawData($badUrl);
        $apiRequest->getJson($badUrl);
    }
}
