<?php

namespace App;

/**
 * Description of Calculator
 *
 * @author Doncho Toromanov
 */
class Calculator
{
    private $operations;
    
    private $persons;
    
    private $personClassMap = [
        'natural' => NaturalPerson::class,
        'legal' => LegalPerson::class
    ];
    
    private $operationIndexMap = [];
    
    public function __construct($operations = [])
    {
        $this->setOperations($operations);
    }

    public function getFees()
    {
        $fees = $this->calculateFees();
        
        return $fees;
    }
    
    public function printResult($fees)
    {
        foreach ($fees as $fee) {
            echo $fee. PHP_EOL;
        }
    }
       
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
    
    private function getPerson($id)
    {
        return $this->persons[$id] ?? null;
    }
    
    private function addPerson($person)
    {
        $this->persons[$person->getId()] = $person;
    }
    
    private function createPerson($type, $id)
    {
        if (isset($this->personClassMap[$type])) {
            if (class_exists($this->personClassMap[$type])) {
                return new $this->personClassMap[$type]($id);
            } else {
                throw new \Exception('Unknown person class in class mapping.');
            }
        } else {
            throw new \Exception('Unknown person type in class mapping.');
        }
    }
    
    public function setOperations($operations)
    {
        $this->operations = $operations;
    }
}
