<?php

namespace App\Configuration;

/**
 * MoneyConfigurationProvider
 *
 * @author Doncho Toromanov
 */
class MoneyConfigurationProvider implements MoneyConfigurationProviderInterface
{
    const DEFAULT_PRECISION = 2;
    const DEFAULT_CURRENCY = 'EUR';

    /**
     * @var array
     */
    protected static $defaultCurrencyPrecisions = [
        'EUR' => 2,
        'USD' => 2,
        'JPY' => 0
        ];
    
    /**
     * @var array
     */
    protected static $defaultSupportedCurrencies = ['EUR', 'USD', 'JPY'];
    
    /**
     * @var array
     */
    protected static $defaultConversionRates = ['EUR:USD' => '1.1497', 'EUR:JPY' => '129.53'];
    
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
     */
    public function __construct(array $currencyPrecisions = null, array $supportedCurrencies = null)
    {
        $this->supportedCurrencies = isset($supportedCurrencies)
            ? $supportedCurrencies
            : self::$defaultSupportedCurrencies;
                
        $this->currencyPrecisions = isset($currencyPrecisions)
            ? $currencyPrecisions
            : self::$defaultCurrencyPrecisions;
        
        $this->conversionRates = self::$defaultConversionRates;
    }

    /**
     * Get default precision for selected currency.
     *
     * @param string $currency
     * @return int
     */
    public function getDefaultPrecision($currency)
    {
        return isset($this->currencyPrecisions[$currency])
            ? $this->currencyPrecisions[$currency]
            : self::DEFAULT_PRECISION;
    }

    public function getSupportedCurrencies()
    {
        return $this->supportedCurrencies;
    }
    
    /**
     * Add supported currency.
     *
     * @param string $currency
     */
    public function addSupportedCurrency($currency)
    {
        if (!isset($this->supportedCurrencies[$currency])) {
            $this->supportedCurrencies[] = $currency;
        }
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
     * Add conversion rate.
     *
     * @param string $currFrom
     * @param string $currTo
     * @param string $rate
     */
    public function addConversionRate($currFrom, $currTo, $rate)
    {
        $this->conversionRates[$currFrom . ':' . $currTo] = $rate;
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
