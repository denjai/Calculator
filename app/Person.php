<?php

namespace App\Entities;

use App\MoneyCalculator;
use App\Currency;
use App\Entities\Operation;

/**
 * Entity for Person
 *
 * @author Doncho Toromanov
 */
class Person
{
    const CASH_IN_FEE = 0.03;
    const CASH_IN_FEE_MAX = 5;
    const CASH_IN_FEE_MAX_CURRENCY = 'EUR';
    
    const CASH_OUT_FEE = 0.3;
    
    /**
     * @var array 
     */
    protected $operations = [];
    
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var \App\MoneyCalculator 
     */
    protected $calculator;

    /**
     * Class constructor.
     * 
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->calculator = new MoneyCalculator();
    }
    
    /**
     * Get person ID.
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Calculate commission fee for given operation.
     * 
     * @param \App\Entity\Operation $operation
     * @return array
     * @throws \Exception
     */
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
    
    /**
     * Add operation.
     * 
     * @param \App\Entities\Operation $operation
     */
    protected function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }
    
    /**
     * Calculate and return cash in fee for given operation.
     * 
     * @param \App\Entities\Operation $operation
     * @return string
     */
    protected function calculateCashInFee(Operation $operation)
    {
        $feeMultiplier = $this->calculator->divide(self::CASH_IN_FEE, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        
        $maxFee = Currency::convert(self::CASH_IN_FEE_MAX, self::CASH_IN_FEE_MAX_CURRENCY, $operation->getCurrency());
        
        return min($fee, $maxFee);
    }
    
    /**
     * Calculate and return cash out fee for given operation.
     * 
     * @param \App\Entities\Operation $operation
     * @return string
     */
    protected function calculateCashOutFee(Operation $operation)
    {
        $feeMultiplier = $this->calculator->divide(self::CASH_OUT_FEE, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
      
        return $fee;
    }
}
