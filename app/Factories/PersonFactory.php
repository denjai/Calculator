<?php

namespace App\Factories;

/**
 * PersonFactory
 *
 * @author Doncho Toromanov
 */
class PersonFactory
{
    
    /**
     * @var array
     */
    private static $personClassMap = [
        'natural' => \App\NaturalPerson::class,
        'legal' => \App\LegalPerson::class
    ];
    
    /**
     * Create new person entity.
     *
     * @param string $type
     * @param int $id
     * @param \App\MoneyCalculator $calculator
     *
     * @return \App\Person
     *
     * @throws \Exception
     */
    public function create($type, $id, $calculator)
    {
        if (isset(self::$personClassMap[$type])) {
            if (class_exists(self::$personClassMap[$type])) {
                return new self::$personClassMap[$type]($id, $calculator);
            }
            throw new \Exception('Unknown person class in class mapping.');
        }
        
        throw new \Exception('Unknown person type in class mapping.');
    }
}
