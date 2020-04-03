<?php

namespace App;

/**
 * Description of Person
 *
 * @author Doncho Toromanov
 */
class Person
{
    const CASH_IN_FEE = 0.03;
    const CASH_IN_FEE_MAX = 5;
    const CASH_IN_FEE_MAX_CURRENCY = 'EUR';
    
    const CASH_OUT_FEE = 0.3;

    protected $operations = [];
    
    private $id;
    
    protected $calculator;

    public function __construct($id)
    {
        $this->id = $id;
        $this->calculator = new MoneyCalculator();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function calculateFee(Operation $operation)
    {
        $this->addOperation($operation);

        if ($operation->getType() == Operation::CASH_IN) {
            $fee = $this->calculateCashInFee($operation);
            return Currency::round($fee, $operation->getCurrency());
        }
        if ($operation->getType() == Operation::CASH_OUT) {
            $fee = $this->calculateCashOutFee($operation);
            return Currency::round($fee, $operation->getCurrency());
        }
        
        throw new \Exception('Unknown operation type: ' . $operation->getType());
    }
    
    protected function addOperation($operation)
    {
        $this->operations[] = $operation;
    }
    
    protected function calculateCashInFee($operation)
    {
        $feeMultiplier = $this->calculator->divide(self::CASH_IN_FEE, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        
        $maxFee = Currency::convert(self::CASH_IN_FEE_MAX, self::CASH_IN_FEE_MAX_CURRENCY, $operation->getCurrency());
        
        return min($fee, $maxFee);
    }
    
    protected function calculateCashOutFee(Operation $operation)
    {
        $feeMultiplier = $this->calculator->divide(self::CASH_OUT_FEE, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
      
        return $fee;
    }
}
