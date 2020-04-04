<?php

namespace App\Entities;

use App\Currency;
use App\Entities\Operation;
/**
 * Entity for NaturalPerson
 *
 * @author Doncho Toromanov
 */
class NaturalPerson extends Person
{
    const CASH_OUT_FEE = 0.3;
    const FREE_CASH_OUT_WEEKLY_COUNT_LIMIT = 3;
    const FREE_CASH_OUT_WEEKLY_AMOUNT_LIMIT = 1000;
    
    /**
     * @var array
     */
    private $cashOutWeekData;
   
    /**
     * Add operation.
     * 
     * @param \App\Entities\Operation $operation
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
        if ($this->getCashOutCountForWeek($operation->getWeekNumber()) > self::FREE_CASH_OUT_WEEKLY_COUNT_LIMIT) {
            return parent::calculateCashOutFee($operation); //default
        }
        
        $amountForWeek = $this->getCashOutAmountForWeek($operation->getWeekNumber());
        if ($this->calculator->compare($amountForWeek, self::FREE_CASH_OUT_WEEKLY_AMOUNT_LIMIT) == -1) {
            return '0';
        }
        
        $amountForWeek = Currency::convert($amountForWeek, Currency::DEFAULT_CURRENCY, $operation->getCurrency());
        $freeOfChargeLimit = Currency::convert(self::FREE_CASH_OUT_WEEKLY_AMOUNT_LIMIT, Currency::DEFAULT_CURRENCY, $operation->getCurrency());
        $amountForWeekPrev = $this->calculator->subtract($amountForWeek, $operation->getAmount());
        if ($this->calculator->compare($amountForWeekPrev, $freeOfChargeLimit) == 1) {
            $feeMultiplier = $this->calculator->divide(self::CASH_OUT_FEE, 100);

            return $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        } else {
            $feeMultiplier = $this->calculator->divide(self::CASH_OUT_FEE, 100);
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
            $amount = Currency::convert($operation->getAmount(), $operation->getCurrency());

            $this->cashOutWeekData[$weekNumber]['count']++;
            $this->cashOutWeekData[$weekNumber]['amount'] = $this->calculator->add($this->cashOutWeekData[$weekNumber]['amount'], $amount);
        } else {
            $this->cashOutWeekData[$weekNumber]['count'] = 1;
            $this->cashOutWeekData[$weekNumber]['amount'] = Currency::convert($operation->getAmount(), $operation->getCurrency());
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
