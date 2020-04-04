<?php

namespace App;

use App\Entities\Operation;
use App\Entities\LegalPerson;
use App\Entities\NaturalPerson;

/**
 * Commission fee calculator.
 *
 * @author Doncho Toromanov
 */
class Calculator
{
    /**
     * @var array 
     */
    private $operations;
    
    /**
     * @var array
     */
    private $persons;
    
    /**
     * @var array 
     */
    private $personClassMap = [
        'natural' => NaturalPerson::class,
        'legal' => LegalPerson::class
    ];
    
    /**
     * @var array 
     */
    private $operationIndexMap = [];
    
    /**
     * Class constructor.
     * 
     * @param array $operations
     */
    public function __construct($operations = [])
    {
        $this->setOperations($operations);
    }
    
    /**
     * Get calculated commission fees.
     * 
     * @return array
     */
    public function getFees()
    {
        return $this->calculateFees();
    }
    
    /**
     * Outputs result to sdout.
     * 
     * @param array $fees
     */
    public function outputResult($fees)
    {
        foreach ($fees as $fee) {
            echo $fee. PHP_EOL;
        }
    }
       
    /**
     * Calculate commission fees. 
     * 
     * @return array
     */
    private function calculateFees()
    {
        $fees = [];
        foreach ($this->operations as $operationData) {
            $operation = new Operation($operationData[0], $operationData[3], $operationData[4], $operationData[5]);
            $person = $this->getPerson($operationData[1]);
            if (!$person) {
                $person = $this->createPerson($operationData[2], $operationData[1]);
                $this->addPerson($person);
            }
            $fees[] = $person->calculateFee($operation);
        }
        
        return $fees;
    }
    
    /**
     * Get person by ID.
     * 
     * @param int $id
     * @return \App\Entities\Person
     */
    private function getPerson($id)
    {
        return $this->persons[$id] ?? null;
    }
    
    /**
     * Add person.
     * 
     * @param \App\Entities\Person $person
     */
    private function addPerson($person)
    {
        $this->persons[$person->getId()] = $person;
    }
    
    /**
     * Create new person entity.
     * 
     * @param string $type
     * @param int $id
     * @return \App\Entities\Person
     * @throws \Exception
     */
    private function createPerson($type, $id)
    {
        if (isset($this->personClassMap[$type])) {
            if (class_exists($this->personClassMap[$type])) {
                return new $this->personClassMap[$type]($id);
            }              
            throw new \Exception('Unknown person class in class mapping.');
        } 
        
        throw new \Exception('Unknown person type in class mapping.');
        
    }
    
    /**
     * Set operations to be calculated.
     * 
     * @param array $operations
     */
    public function setOperations($operations)
    {
        $this->operations = $operations;
    }
}
