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
    public function addOperation(Operation $operation)
    {
        parent::addOperation($operation);
        
        if ($operation->getType() === Operation::CASH_OUT) {
            $this->addCashOutWeekData($operation);
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
