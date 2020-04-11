<?php

namespace App;

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
     * @param MoneyConfigurationProviderInterface $configurationProvider
     * @param int $scale
     */
    public function __construct(MoneyConfigurationProviderInterface $configurationProvider, $scale = 14)
    {
        $this->scale = $scale;
        
        $this->configurationProvider = $configurationProvider;
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
    
    /**
     * Divide
     *
     * @param string $amount
     * @param string $divisor
     *
     * @return string
     */
    public function divide($amount, $divisor)
    {
        return bcdiv($amount, (string) $divisor, $this->scale);
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
        
        if ($currFrom === $currTo) {
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
        
        throw new \Exception('Currency convertion rate not found: ' . $rate);
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
   
        if ($precision === 0) {
            return number_format(ceil($amount), $precision, '.', '');
        }
        
        $precisionMultiplier = pow(10, $precision);
        $amount = ceil($this->multiply($amount, $precisionMultiplier));

        return number_format($this->divide($amount, $precisionMultiplier), $precision, '.', '');
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
