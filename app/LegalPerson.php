<?php

namespace App;

use App\Person;

/**
 * Description of LegalPerson
 *
 * @author Doncho Toromanov
 */
class LegalPerson extends Person
{
    const CASH_OUT_FEE = 0.3;
    const CASH_OUT_FEE_MIN = 0.5;
    const CASH_OUT_FEE_MIN_CURRENCY = 'EUR';
    
    protected function calculateCashOutFee(Operation $operation)
    {
        $feeMultiplier = $this->calculator->divide(self::CASH_OUT_FEE, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        $minFee = Currency::convert(self::CASH_OUT_FEE_MIN, self::CASH_OUT_FEE_MIN_CURRENCY, $operation->getCurrency());
        
        return max($fee, $minFee);
    }
}
