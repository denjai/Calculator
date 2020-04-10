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
     * Class constructor.
     *
     * @param int $id
     * @param \App\MoneyCalculator $calculator
     */
    public function __construct($id, $calculator)
    {
        $this->id = $id;
        $this->calculator = $calculator;
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
     * Add operation.
     *
     * @param \App\Operation $operation
     */
    public function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }
}
