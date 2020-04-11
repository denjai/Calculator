<?php

namespace App\Configuration;

/**
 * MoneyConfigurationProvider
 *
 * @author Doncho Toromanov
 */
class MoneyConfigurationProvider implements MoneyConfigurationProviderInterface
{
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var array
     */
    protected $currencyPrecisions;
    
    /**
     * @var array
     */
    protected $supportedCurrencies;
    
    /**
     * @var array
     */
    protected $conversionRates;

    /**
     * Class constructor.
     *
     * @param array $currencyPrecisions
     * @param array $supportedCurrencies
     * @param array $conversionRates
     */
    public function __construct(array $currencyPrecisions, array $supportedCurrencies, array $conversionRates)
    {
        $this->supportedCurrencies = $supportedCurrencies;
                
        $this->currencyPrecisions = $currencyPrecisions;
        
        $this->conversionRates = $conversionRates;
    }

    /**
     * Get default precision for selected currency.
     *
     * @param string $currency
     * @return int
     */
    public function getDefaultPrecision($currency, $defaultPrecision = 2)
    {
        return isset($this->currencyPrecisions[$currency])
            ? $this->currencyPrecisions[$currency]
            : $defaultPrecision;
    }

    public function getSupportedCurrencies()
    {
        return $this->supportedCurrencies;
    }
    
    /**
     * Get currency conversion rate.
     *
     * @param string $currFrom
     * @param string $currTo
     *
     * @return string|boolean
     */
    public function getConversionRate($currFrom, $currTo)
    {
        $key = $currFrom . ':' . $currTo;
        if (isset($this->conversionRates[$key])) {
            return $this->conversionRates[$key];
        }
        
        return false;
    }
    
    /**
     * Get default currency.
     *
     * @return string
     */
    public function getDefaultCurrency()
    {
        return self::DEFAULT_CURRENCY;
    }
}
