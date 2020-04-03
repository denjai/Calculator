<?php

namespace App;

/**
 * Description of Money
 *
 * @author Doncho Toromanov
 */
class MoneyCalculator
{
    /**
     * @var string
     */
    private $scale;

    /**
     * @param int $scale
     */
    public function __construct($scale = 14)
    {
        $this->scale = $scale;
    }
    
    public function add($amount, $addend)
    {
        return bcadd($amount, $addend, $this->scale);
    }
    
    public function compare($a, $b)
    {
        return bccomp($a, $b, $this->scale);
    }
    
    /**
     *
     * @param $amount
     * @param $subtrahend
     *
     * @return string
     */
    public function subtract($amount, $subtrahend)
    {
        return bcsub($amount, $subtrahend, $this->scale);
    }

    public function multiply($amount, $multiplier)
    {
        return bcmul($amount, (string) $multiplier, $this->scale);
    }

    public function divide($amount, $divisor)
    {
        return bcdiv($amount, (string) $divisor, $this->scale);
    }
}
