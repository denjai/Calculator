<?php

namespace App;

/**
 * Money calculator based on BC Math extension.
 *
 * @author Doncho Toromanov
 */
class MoneyCalculator
{
    /**
     * @var int
     */
    private $scale;

    /**
     * @param int $scale
     */
    public function __construct($scale = 14)
    {
        $this->scale = $scale;
    }
    
    /**
     * Add
     *
     * @param string $amount
     * @param string $addend
     *
     * @return string
     */
    public function add($amount, $addend)
    {
        return bcadd($amount, $addend, $this->scale);
    }
    
    /**
     * Compare
     *
     * @param string $a
     * @param string $b
     *
     * @return int Returns:
     *                -1 (if $a < $b)
     *                 0 (if $a = $b)
     *                 1 (if $a > $b)
     */
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
    
    /**
     * Multiply
     *
     * @param string $amount
     * @param string $multiplier
     *
     * @return string
     */
    public function multiply($amount, $multiplier)
    {
        return bcmul($amount, (string) $multiplier, $this->scale);
    }

    public function divide($amount, $divisor)
    {
        return bcdiv($amount, (string) $divisor, $this->scale);
    }
    
    /**
     * Set scale
     *
     * @param int $scale
     *
     * @return MoneyCalculator
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * Get scale
     *
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }
}
