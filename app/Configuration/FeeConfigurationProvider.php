<?php

namespace App\Configuration;

/**
 * FeeConfigurationProvider
 *
 * @author Doncho Toromanov
 */
class FeeConfigurationProvider implements FeeConfigurationProviderInterface
{
    /**
     * @var string
     */
    protected static $defaultCashOutFee = '0.3';
    
    /**
     * @var string
     */
    protected static $defaultCashInFee = '0.03';
    
    /**
     * @var array
     */
    protected static $defaultMaxCashInFee = ['5', 'EUR'];
    
    /**
     * @var array
     */
    protected static $defaultMinCashOutFee = ['0.5', 'EUR'];
    
    /**
     * @var array
     */
    protected static $weeklyCashOutAmountLimit = ['1000', 'EUR'];
    
    /**
     * @var int
     */
    protected static $weeklyCashOutCountLimit = 3;
    
    /**
     * Get cash out fee percentage.
     *
     * @return string
     */
    public function getCashOutFeePercentage()
    {
        return self::$defaultCashOutFee;
    }
    
    /**
     * Get cash in fee percentage.
     *
     * @return string
     */
    public function getCashInFeePercentage()
    {
        return self::$defaultCashInFee;
    }
    
    /**
     * Get maximum cash in fee.
     *
     * @return string
     */
    public function getMaxCashInFee()
    {
        return self::$defaultMaxCashInFee;
    }
    
    /**
     * Get minimum cash out fee.
     *
     * @return string
     */
    public function getMinCashOutFee()
    {
        return self::$defaultMinCashOutFee;
    }
    
    /**
     * Get free cash out weekly count limit.
     *
     * @return int
     */
    public function getWeeklyCashOutCountLimit()
    {
        return self::$weeklyCashOutCountLimit;
    }
    
    /**
     * Get free cash out weekly amount limit.
     *
     * @return string
     */
    public function getWeeklyCashOutAmountLimit()
    {
        return self::$weeklyCashOutAmountLimit;
    }
}
