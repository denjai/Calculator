<?php

namespace App;

use App\Configuration\MoneyConfigurationProvider;
use App\Configuration\MoneyConfigurationProviderInterface;

/**
 * Money calculator based on BC Math extension.
 *
 * @author Doncho Toromanov
 */
class MoneyCalculator
{
    /**
     * @var int
     */
    private $scale;

    /**
     * @var \App\Configuration\MoneyConfigurationProviderInterface
     */
    private $configurationProvider;

    /**
     * @param int $scale
     */
    public function __construct($scale = 14, $configurationProvider = null)
    {
        $this->scale = $scale;
        
        $this->configurationProvider = $configurationProvider !== null
                ? $configurationProvider
                : new MoneyConfigurationProvider();
    }
    
    /**
     * Add
     *
     * @param string $amount
     * @param string $addend
     *
     * @return string
     */
    public function add($amount, $addend)
    {
        return bcadd($amount, $addend, $this->scale);
    }
    
    /**
     * Compare
     *
     * @param string $a
     * @param string $b
     *
     * @return int Returns:
     *                -1 (if $a < $b)
     *                 0 (if $a = $b)
     *                 1 (if $a > $b)
     */
    public function compare($a, $b)
    {
        return bccomp($a, $b, $this->scale);
    }
    
    /**
     *
     * @param $amount
     * @param $subtrahend
     *
     * @return string
     */
    public function subtract($amount, $subtrahend)
    {
        return bcsub($amount, $subtrahend, $this->scale);
    }
    
    /**
     * Multiply
     *
     * @param string $amount
     * @param string $multiplier
     *
     * @return string
     */
    public function multiply($amount, $multiplier)
    {
        return bcmul($amount, (string) $multiplier, $this->scale);
    }

    public function divide($amount, $divisor)
    {
        return bcdiv($amount, (string) $divisor, $this->scale);
    }
    
    /**
     * Set scale
     *
     * @param int $scale
     *
     * @return MoneyCalculator
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * Get scale
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }
    
    /**
     * Convert money amount from one currency to another.
     *
     * @param string $amount
     * @param string $currFrom
     * @param string $currTo
     *
     * @return string
     * @throws \Exception
     */
    public function convert($amount, $currFrom, $currTo = null)
    {
        $currTo = $currTo ?? $this->configurationProvider->getDefaultCurrency();
        
        if ($currFrom == $currTo) {
            return $amount;
        }
        
        $rate = $this->configurationProvider->getConversionRate($currFrom, $currTo);
        if ($rate !== false) {
            return $this->multiply($amount, $rate);
        }
        
        $rate = $this->configurationProvider->getConversionRate($currTo, $currFrom);
        if ($rate !== false) {
            return $this->divide($amount, $rate);
        }
        
        throw new \Exception('Currency convertion rate not found: ' . $key);
    }
    
    /**
     * Round money amount to the precision of the selected currency.
     *
     * @param string $amount
     * @param string $currency
     * @return string
     */
    public function round($amount, $currency)
    {
        $precision = $this->configurationProvider->getDefaultPrecision($currency);
   
        if ($precision == 0) {
            return ceil($amount);
        }
        
        $precisionMultiplier = pow(10, $precision);
        $amount = ceil($this->multiply($amount, $precisionMultiplier));

        return number_format($this->divide($amount, $precisionMultiplier), $precision);
    }
    
    /**
     * Set money configuration provider.
     *
     * @param MoneyConfigurationProviderInterface $provider
     */
    public function setConfigurationProvider(MoneyConfigurationProviderInterface $provider)
    {
        $this->configurationProvider = $provider;
    }
    
    /**
     * Get default currency.
     *
     * @return string
     */
    public function getDefaultCurrency()
    {
        return $this->configurationProvider->getDefaultCurrency();
    }
}
