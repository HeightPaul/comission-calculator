<?php

namespace Tests\Functional\Lib;

use \PHPUnit\Framework\TestCase;
use ComissionCli\Lib\ApiRequest;
use ComissionCli\Lib\ComissionHelper;

class ApiRequestTest extends TestCase
{
    public function testSuccessfulApiRequestForCountryCodeByBinProvider(): void
    {
        $apiRequest = new ApiRequest();
        $binJson = $apiRequest->getJson(sprintf('%s/%s', ComissionHelper::DEFAULT_BIN_PROVIDER_URL, '516793'));
        self::assertEquals('LT', $binJson['country']['alpha2']);
    }

    public function testFailedApiRequestForRateByExchangeRatesProvider(): void
    {
        $apiRequest = new ApiRequest();
        $ratesJson = $apiRequest->getJson(ComissionHelper::DEFAULT_CURRENCY_RATES_PROVIDER_URL);
        self::assertEquals(101, $ratesJson['error']['code']);
    }
}
