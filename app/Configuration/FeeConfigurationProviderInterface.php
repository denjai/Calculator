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
}
