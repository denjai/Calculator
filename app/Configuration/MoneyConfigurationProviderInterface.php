<?php

namespace App\Configuration;

/**
 * Description of MoneyConfigurationProviderInterface
 *
 * @author Doncho Toromanov
 */
interface MoneyConfigurationProviderInterface
{
     
    /**
     * @param string $currency
     * @return int
     */
    public function getDefaultPrecision($currency);

    /**
     * @return array
     */
    public function getSupportedCurrencies();
    
    /**
     *
     * @param string $currFrom
     * @param string $currTo
     *
     * @return string
     */
    public function getConversionRate($currFrom, $currTo);
}
