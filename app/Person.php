<?php

namespace App;

use App\MoneyCalculator;
use App\Operation;
use App\Configuration\FeeConfigurationProvider;
use App\Configuration\FeeConfigurationProviderInterface;

/**
 * Entity for Person
 *
 * @author Doncho Toromanov
 */
class Person
{
    
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
     * @var \App\Configuration\FeeConfigurationProviderInterface
     */
    protected $configurationProvider;

    /**
     * Class constructor.
     *
     * @param int $id
     */
    public function __construct($id, $configurationProvider = null)
    {
        $this->id = $id;
        $this->calculator = new MoneyCalculator();
        
        $this->configurationProvider = $configurationProvider !== null
                ? $configurationProvider
                : new FeeConfigurationProvider();
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
            return $this->calculator->round($fee, $operation->getCurrency());
        }
        if ($operation->getType() == Operation::CASH_OUT) {
            $fee = $this->calculateCashOutFee($operation);
            return $this->calculator->round($fee, $operation->getCurrency());
        }
        
        throw new \Exception('Unknown operation type: ' . $operation->getType());
    }
    
    /**
     * Add operation.
     *
     * @param \App\Operation $operation
     */
    protected function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }
    
    /**
     * Calculate and return cash in fee for given operation.
     *
     * @param \App\Operation $operation
     * @return string
     */
    protected function calculateCashInFee(Operation $operation)
    {
        $cashInFeePercentage = $this->configurationProvider->getCashInFeePercentage();
        $feeMultiplier = $this->calculator->divide($cashInFeePercentage, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        
        list($maxCashInFee, $maxCashInFeeCurrency) = $this->configurationProvider->getMaxCashInFee();
        $maxFee = $this->calculator->convert($maxCashInFee, $maxCashInFeeCurrency, $operation->getCurrency());
        
        return min($fee, $maxFee);
    }
    
    /**
     * Calculate and return cash out fee for given operation.
     *
     * @param \App\Operation $operation
     * @return string
     */
    protected function calculateCashOutFee(Operation $operation)
    {
        $cashOutFeePercentage = $this->configurationProvider->getCashOutFeePercentage();
        $feeMultiplier = $this->calculator->divide($cashOutFeePercentage, 100);
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
      
        return $fee;
    }
    
    /**
     * Set commission fee configuration provider.
     *
     * @param FeeConfigurationProviderInterface $provider
     */
    public function setConfigurationProvider(FeeConfigurationProviderInterface $provider)
    {
        $this->configurationProvider = $provider;
    }
}
