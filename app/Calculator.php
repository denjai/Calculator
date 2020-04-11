<?php

namespace App;

use App\Validation\InputValidator;
use App\Configuration\FeeConfigurationProviderInterface;
use App\Repositories\PersonRepositoryInterface;
use App\Factories\PersonFactory;
use App\MoneyCalculator;

/**
 * Commission fee calculator.
 *
 * @author Doncho Toromanov
 */
class Calculator
{
    /**
     * @var \App\Validation\InputValidator
     */
    private $validator;
    
    /**
     * @var \App\MoneyCalculator
     */
    protected $calculator;
    
    /**
     * @var \App\Configuration\FeeConfigurationProviderInterface
     */
    protected $configurationProvider;
    
    /**
     * @var \App\Repositories\PersonRepositoryInterface
     */
    private $personRepository;
    
    /**
     * @var \App\Factories\PersonFactory
     */
    private $personFactory;
    
    /**
     * Class constructor.
     *
     * @param \App\Validation\InputValidator $validator
     * @param \App\Configuration\FeeConfigurationProviderInterface $configurationProvider
     * @param \App\MoneyCalculator $calculator
     * @param \App\Repositories\PersonRepositoryInterface
     * @param \App\Factories\PersonFactory
     */
    public function __construct(
        InputValidator $validator,
        FeeConfigurationProviderInterface $configurationProvider,
        MoneyCalculator $calculator,
        PersonRepositoryInterface $personRepository,
        PersonFactory $personFactory
    ) {
        $this->validator = $validator;
        $this->configurationProvider = $configurationProvider;
        $this->calculator = $calculator;
        $this->personRepository = $personRepository;
        $this->personFactory = $personFactory;
    }
        
    /**
     * Calculates and returns commission fees for given set of opearations.
     *
     * @param array $operations
     *
     * @return array
     */
    public function calculateFees($operations)
    {
        $this->validator->validateOperations($operations);
        
        $fees = [];
        foreach ($operations as $operationData) {
            $operation = new Operation($operationData[0], $operationData[3], $operationData[4], $operationData[5]);
            $person = $this->personRepository->getPerson($operationData[1]);
            if (!$person) {
                $person = $this->personFactory->create($operationData[2], $operationData[1], $this->calculator);
                $this->personRepository->addPerson($person);
            }
            $person->addOperation($operation);
                            
            $fees[] = $this->calculateFee($operation, $person);
        }
        $this->personRepository->deleteAll();
        
        return $fees;
    }
    
    /**
     * Calculate commission fee for given operation.
     *
     * @param \App\Entity\Operation $operation
     * @param \App\Person $person
     *
     * @return array
     * @throws \Exception
     */
    private function calculateFee(Operation $operation, Person $person)
    {
        if ($operation->getType() === Operation::CASH_IN) {
            $fee = $this->calculateCashInFee($operation);
            return $this->calculator->round($fee, $operation->getCurrency());
        }
        if ($operation->getType() === Operation::CASH_OUT) {
            $fee = $this->calculateCashOutFee($operation, $person);
            return $this->calculator->round($fee, $operation->getCurrency());
        }
        
        throw new \Exception('Unknown operation type: ' . $operation->getType());
    }
    
    /**
     * Calculate and return cash in fee for given operation.
     *
     * @param \App\Operation $operation
     * @return string
     */
    private function calculateCashInFee(Operation $operation)
    {
        $cashInFeePercentage = $this->configurationProvider->getCashInFeePercentage();
        $feeMultiplier = $this->calculator->divide($cashInFeePercentage, '100');
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        
        list($maxCashInFee, $maxCashInFeeCurrency) = $this->configurationProvider->getMaxCashInFee();
        $maxFee = $this->calculator->convert($maxCashInFee, $maxCashInFeeCurrency, $operation->getCurrency());
        
        return min($fee, $maxFee);
    }
    
    /**
     * Calculate and return cash out fee for given operation.
     *
     * @param \App\Operation $operation
     * @param \App\Person $person
     *
     * @return string
     */
    private function calculateCashOutFee(Operation $operation, Person $person)
    {
        if ($person instanceof LegalPerson) {
            return $this->calculateCashOutFeeForLegalPerson($operation);
        }
        if ($person instanceof NaturalPerson) {
            return $this->calculateCashOutFeeForNaturalPerson($operation, $person);
        }
        
        return $this->calculateCashOutFeeDefault();
    }
    
    /**
     * Calculate and return cash out fee for legal person for given operation.
     *
     * @param \App\Operation $operation
     * @return string
     */
    private function calculateCashOutFeeForLegalPerson(Operation $operation)
    {
        $cashOutFeePercentage = $this->configurationProvider->getCashOutFeePercentage();
        $feeMultiplier = $this->calculator->divide($cashOutFeePercentage, '100');
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);
        
        list($minCashOutFee, $minCashOutFeeCurrency) = $this->configurationProvider->getMinCashOutFee();
        $minFee = $this->calculator->convert($minCashOutFee, $minCashOutFeeCurrency, $operation->getCurrency());
        
        return max($fee, $minFee);
    }
    
    /**
     * Calculate and return cash out fee for natural person for given operation.
     *
     * @param \App\Operation $operation
     * @param \App\Person $person
     * @return string
     */
    private function calculateCashOutFeeForNaturalPerson(Operation $operation, Person $person)
    {
        //check free weekly cash out count limit
        if ($person->getCashOutCountForWeek($operation->getWeekNumber())
                > $this->configurationProvider->getWeeklyCashOutCountLimit()) {
            //operation count is above free weekly acount limit
            return $this->calculateCashOutFeeDefault($operation); //use default rates
        }
        
        $amountForWeek = $person->getCashOutAmountForWeek($operation->getWeekNumber());
        list($weeklyLimit, $weeklyLimitCurrency) = $this->configurationProvider->getWeeklyCashOutAmountLimit();
        $weeklyLimitConverted = $this->calculator->convert($weeklyLimit, $weeklyLimitCurrency);
        if ($this->calculator->compare($amountForWeek, $weeklyLimitConverted) === -1) {
            //operation money amount is below free weekly amount limit
            return '0';
        }
        
        $defaultCurrency = $this->calculator->getDefaultCurrency();
        $amountForWeek = $this->calculator->convert($amountForWeek, $defaultCurrency, $operation->getCurrency());
        $freeOfChargeLimit = $this->calculator->convert($weeklyLimit, $weeklyLimitCurrency, $operation->getCurrency());
        $amountForWeekPrev = $this->calculator->subtract($amountForWeek, $operation->getAmount());
        if ($this->calculator->compare($amountForWeekPrev, $freeOfChargeLimit) === 1) {
            //calculate commission fee on the whole operation amount
            return $this->calculateCashOutFeeDefault($operation);
        } else {
            //calculate commission fee on part of operation amount that is over the free weekly amount limit
            $cashOutFeePercentage = $this->configurationProvider->getCashOutFeePercentage();
            $feeMultiplier = $this->calculator->divide($cashOutFeePercentage, '100');
            $amount = $this->calculator->subtract($amountForWeek, $freeOfChargeLimit);
            
            return $this->calculator->multiply($amount, $feeMultiplier);
        }
    }
    
    /**
     * Calculate and return default  cash out fee for given operation.
     *
     * @param \App\Operation $operation
     * @return string
     */
    protected function calculateCashOutFeeDefault(Operation $operation)
    {
        $cashOutFeePercentage = $this->configurationProvider->getCashOutFeePercentage();
        $feeMultiplier = $this->calculator->divide($cashOutFeePercentage, '100');
        $fee = $this->calculator->multiply($operation->getAmount(), $feeMultiplier);

        return $fee;
    }
}
