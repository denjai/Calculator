<?php

namespace App;

/**
 * Entity for NaturalPerson
 *
 * @author Doncho Toromanov
 */
class NaturalPerson extends Person
{
    /**
     * @var array
     */
    private $cashOutWeekData;
   
    /**
     * Add operation.
     *
     * @param \App\Operation $operation
     */
    protected function addOperation(Operation $operation)
    {
        parent::addOperation($operation);
        
        if ($operation->getType() == Operation::CASH_OUT) {
            $this->addCashOutWeekData($operation);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    protected function calculateCashOutFee(Operation $operation)
    {
        //check free weekly cash out count limit
        if ($this->getCashOutCountForWeek($operation->getWeekNumber())
                > $this->configurationProvider->getWeeklyCashOutCountLimit()) {
            //operation count is above free weekly acount limit
            return parent::calculateCashOutFee($operation); //use default rates
        }
        
        $amountForWeek = $this->getCashOutAmountForWeek($operation->getWeekNumber());
        list($weeklyLimit, $weeklyLimitCurrency) = $this->configurationProvider->getWeeklyCashOutAmountLimit();
        $weeklyLimitConverted = $this->calculator->convert($weeklyLimit, $weeklyLimitCurrency);
        if ($this->calculator->compare($amountForWeek, $weeklyLimitConverted) == -1) {
            //operation money amount is below free weekly amount limit
            return '0';
        }
        
        $defaultCurrency = $this->calculator->getDefaultCurrency();
        $amountForWeek = $this->calculator->convert($amountForWeek, $defaultCurrency, $operation->getCurrency());
        $freeOfChargeLimit = $this->calculator->convert($weeklyLimit, $weeklyLimitCurrency, $operation->getCurrency());
        $amountForWeekPrev = $this->calculator->subtract($amountForWeek, $operation->getAmount());
        
        if ($this->calculator->compare($amountForWeekPrev, $freeOfChargeLimit) == 1) {
            //calculate commission fee on the whole operation amount
            return parent::calculateCashOutFee($operation);
        } else {
            //calculate commission fee on part of operation amount that is over the free weekly amount limit
            $cashOutFeePercentage = $this->configurationProvider->getCashOutFeePercentage();
            $feeMultiplier = $this->calculator->divide($cashOutFeePercentage, '100');
            $amount = $this->calculator->subtract($amountForWeek, $freeOfChargeLimit);
            
            return $this->calculator->multiply($amount, $feeMultiplier);
        }
    }
    
    /**
     * Add operation information to weekly cash out data.
     *
     * @param \App\Operation $operation
     */
    private function addCashOutWeekData(Operation $operation)
    {
        $weekNumber = $operation->getWeekNumber();
        if (isset($this->cashOutWeekData[$weekNumber])) {
            $amount = $this->calculator->convert($operation->getAmount(), $operation->getCurrency());

            $this->cashOutWeekData[$weekNumber]['count']++;
            $this->cashOutWeekData[$weekNumber]['amount'] = $this->calculator->add($this->cashOutWeekData[$weekNumber]['amount'], $amount);
        } else {
            $this->cashOutWeekData[$weekNumber]['count'] = 1;
            $this->cashOutWeekData[$weekNumber]['amount'] = $this->calculator->convert($operation->getAmount(), $operation->getCurrency());
        }
    }
    
    /**
     * Get cash out count for selected week.
     *
     * @param string $weekId
     *
     * @return int
     */
    public function getCashOutCountForWeek($weekId)
    {
        if (isset($this->cashOutWeekData[$weekId]['count'])) {
            return $this->cashOutWeekData[$weekId]['count'];
        }
        
        return 0;
    }
    
    /**
     * Get cash out amount for selected week.
     *
     * @param string $weekId
     *
     * @return string
     */
    public function getCashOutAmountForWeek($weekId)
    {
        if (isset($this->cashOutWeekData[$weekId]['amount'])) {
            return $this->cashOutWeekData[$weekId]['amount'];
        }
        
        return '0';
    }
}
