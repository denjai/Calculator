<?php

namespace App;

/**
 * Description of Currency
 *
 * @author Doncho Toromanov
 */
class Currency
{
    //conversion rates
    const RATES = ['EUR:USD' => 1.1497, 'EUR:JPY' => 129.53];
    const PRECISION = ['EUR' => 2, 'USD' => 2, 'JPY' => 0];
    const DEFAULT_PRECISION = 2;
    const DEFAULT_CURRENCY = 'EUR';
    
    public static function convert($amount, $currFrom, $currTo = self::DEFAULT_CURRENCY)
    {
        if ($currFrom == $currTo) {
            return $amount;
        }
        
        $calculator = new MoneyCalculator();
        $key = $currFrom.':'.$currTo;
        if (isset(self::RATES[$key])) {
            return $calculator->multiply($amount, self::RATES[$key]);
        }
        
        $key = $currTo.':'.$currFrom;
        if (isset(self::RATES[$key])) {
            return $calculator->divide($amount, self::RATES[$key]);
        }
        
        throw new \Exception('Currency convertion rate not found: ' . $key);
    }
    
    public static function round($amount, $currency)
    {
        $precision = self::DEFAULT_PRECISION;
        if (isset(self::PRECISION[$currency])) {
            $precision = self::PRECISION[$currency];
        }
        
        if ($precision == 0) {
            return ceil($amount);
        }
        
        $calculator = new MoneyCalculator();
        $precisionMultiplier = pow(10, $precision);
        $amount = ceil($calculator->multiply($amount, $precisionMultiplier));

        return number_format($amount / $precisionMultiplier, $precision);
    }
}
