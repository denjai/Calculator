<?php

namespace App\Configuration;

/**
 *
 * @author Doncho Toromanov
 */
interface FeeConfigurationProviderInterface
{
    /**
     * @return string
     */
    public function getCashInFeePercentage();
    
    /**
     * @return string
     */
    public function getCashOutFeePercentage();
    
    /**
     * @return string
     */
    public function getMaxCashInFee();
    
    
    /**
     * Get minimum cash out fee.
     *
     * @return string
     */
    public function getMinCashOutFee();
    
    /**
     * Get free cash out weekly count limit.
     *
     * @return int
     */
    public function getWeeklyCashOutCountLimit();
    
    /**
     * Get free cash out weekly amount limit.
     *
     * @return string
     */
    public function getWeeklyCashOutAmountLimit();
}
