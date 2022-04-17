<?php

namespace ComissionCli\Lib;

use ComissionCli\Constants\CurrencyCodes;
use ComissionCli\Constants\CountryCodes;
use ComissionCli\Lib\ApiRequest;

/**
 * @brief Commission library class as helper
 */
class ComissionHelper
{
    public const DEFAULT_BIN_PROVIDER_URL = 'https://lookup.binlist.net';
    public const DEFAULT_CURRENCY_RATES_PROVIDER_URL = 'https://api.exchangeratesapi.io/latest';
    private const DEFAULT_CURRENCY_RATES_PROVIDER_ACCESS_KEY = 'NO_KEY';
    private const EU_COMMISION_RATE = 0.01;
    private const NON_EU_COMMISION_RATE = 0.02;
    private const ERROR_LOG_PATH = 'logs/error.log';

    private string $binProviderUrl;
    private ApiRequest $apiRequest;
    private array $exchangeRatesResponse;

    public function __construct(
        string $binProviderUrl = self::DEFAULT_BIN_PROVIDER_URL,
        string $currencyRatesProviderUrl = self::DEFAULT_CURRENCY_RATES_PROVIDER_URL,
        string $currencyRatesProviderAccessKey = self::DEFAULT_CURRENCY_RATES_PROVIDER_ACCESS_KEY
    ) {
        $this->binProviderUrl = $binProviderUrl;
        $this->apiRequest = new ApiRequest();
        $this->exchangeRatesResponse = $this->apiRequest->getJson(
            $currencyRatesProviderUrl,
            sprintf("access_key: %s\r\n", $currencyRatesProviderAccessKey)
        );
        $this->checkLoadedRates();
    }

    /**
     * @return bool|float
     */
    public function getCalculatedComission(array $transaction)
    {
        $binListResponse = $this->apiRequest->getJson(sprintf('%s/%s', $this->binProviderUrl, $transaction['bin']));
        $currency = $transaction['currency'];
        $exchangeRate = $this->exchangeRatesResponse['rates'][$currency] ?? null;
        if(!$this->canApplyEURate($currency, $exchangeRate)) {
            return false;
        } else if ($currency === CurrencyCodes::EUROZONE || $exchangeRate <= 0) {
            $amountFixed = $transaction['amount'];
        } else {
            $amountFixed = $transaction['amount'] / $exchangeRate;
        }
        $comissionRate = $this->isEu($binListResponse['country']['alpha2']) ? self::EU_COMMISION_RATE : self::NON_EU_COMMISION_RATE;
        $res = $amountFixed * $comissionRate;
        return round($res, 2);
    }

    public function isEu(string $country): bool
    {
        switch($country) {
            case CountryCodes::AUSTRIA:
            case CountryCodes::BELGIUM:
            case CountryCodes::BULGARIA:
            case CountryCodes::CYPRUS:
            case CountryCodes::CZECH_REPUBLIC:
            case CountryCodes::GERMANY:
            case CountryCodes::DENMARK:
            case CountryCodes::ESTONIA:
            case CountryCodes::SPAIN:
            case CountryCodes::FINLAND:
            case CountryCodes::FRANCE:
            case CountryCodes::GREECE:
            case CountryCodes::CROATIA :
            case CountryCodes::HUNGARY:
            case CountryCodes::IRELAND:
            case CountryCodes::ITALY:
            case CountryCodes::LITHUANIA:
            case CountryCodes::LUXEMBOURG:
            case CountryCodes::LATVIA:
            case CountryCodes::MALTA:
            case CountryCodes::NETHERLANDS:
            case CountryCodes::POLAND:
            case CountryCodes::PORTUGAL:
            case CountryCodes::ROMANIA:
            case CountryCodes::SWEDEN:
            case CountryCodes::SLOVENIA:
            case CountryCodes::SLOVAKIA:
                return true;
            default:
                return false;
        }
    }

    private function checkLoadedRates(): void
    {
        if(!isset($this->exchangeRatesResponse['rates'])) {
            file_put_contents(self::ERROR_LOG_PATH, sprintf("Exchange rates can't be loaded\n", FILE_APPEND));
        }
    }

    private function canApplyEURate(string $currency, ?float $rate): bool
    {
        return $currency === CurrencyCodes::EUROZONE || isset($rate);
    }
}
