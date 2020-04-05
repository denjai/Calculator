<?php

namespace App;

/**
 * Entity for LegalPerson
 *
 * @author Doncho Toromanov
 */
class LegalPerson extends Person
{
    /**
     * {@inheritdoc}
     */
    protected function calculateCashOutFee(Operation $operation)
    {
        $cashOutFeePercentage = $this->configurationProvider->getCashOutFeePercentage();
        $feeMultiplier = $this->calculator->divide($cashOutFeePercentage, '100');
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        
        list($minCashOutFee, $minCashOutFeeCurrency) = $this->configurationProvider->getMinCashOutFee();
        $minFee = $this->calculator->convert($minCashOutFee, $minCashOutFeeCurrency, $operation->getCurrency());
        
        return max($fee, $minFee);
    }
}
