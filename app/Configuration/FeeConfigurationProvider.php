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
    protected $cashOutFee;
    
    /**
     * @var string
     */
    protected $cashInFee;
    
    /**
     * @var array
     */
    protected $maxCashInFee;
    
    /**
     * @var array
     */
    protected $minCashOutFee;
    
    /**
     * @var array
     */
    protected $weeklyCashOutAmountLimit;
    
    /**
     * @var int
     */
    protected $weeklyCashOutCountLimit;
    
    /**
     * Class constructor.
     *
     * @param string $cashOutFee
     * @param string $cashInFee
     * @param array $maxCashInFee
     * @param array $minCashOutFee
     * @param array $weeklyCashOutAmountLimit
     * @param int $weeklyCashOutCountLimit
     */
    public function __construct(
        $cashOutFee,
        $cashInFee,
        $maxCashInFee,
        $minCashOutFee,
        $weeklyCashOutAmountLimit,
        $weeklyCashOutCountLimit
    ) {
        $this->cashOutFee = $cashOutFee;
        
        $this->cashInFee = $cashInFee;
        
        $this->maxCashInFee = $maxCashInFee;
        
        $this->minCashOutFee = $minCashOutFee;

        $this->weeklyCashOutAmountLimit = $weeklyCashOutAmountLimit;

        $this->weeklyCashOutCountLimit = $weeklyCashOutCountLimit;
    }
    
    /**
     * Get cash out fee percentage.
     *
     * @return string
     */
    public function getCashOutFeePercentage()
    {
        return $this->cashOutFee;
    }
    
    /**
     * Get cash in fee percentage.
     *
     * @return string
     */
    public function getCashInFeePercentage()
    {
        return $this->cashInFee;
    }
    
    /**
     * Get maximum cash in fee.
     *
     * @return string
     */
    public function getMaxCashInFee()
    {
        return $this->maxCashInFee;
    }
    
    /**
     * Get minimum cash out fee.
     *
     * @return string
     */
    public function getMinCashOutFee()
    {
        return  $this->minCashOutFee;
    }
    
    /**
     * Get free cash out weekly count limit.
     *
     * @return int
     */
    public function getWeeklyCashOutCountLimit()
    {
        return $this->weeklyCashOutCountLimit;
    }
    
    /**
     * Get free cash out weekly amount limit.
     *
     * @return string
     */
    public function getWeeklyCashOutAmountLimit()
    {
        return $this->weeklyCashOutAmountLimit;
    }
}
