<?php

namespace App\Entities;

/**
 * Entity for Operation.
 *
 * @author Doncho Toromanov
 */
class Operation
{
    const CASH_OUT = 'cash_out';
    const CASH_IN = 'cash_in';
    
    /**
     * @var string
     */
    private $date;
    
    /**
     * @var string
     */
    private $type;
    
    /**
     * @var string
     */
    private $amount;
    
    /**
     * @var string
     */
    private $currency;
    
    /**
     * Class constructor.
     *
     * @param string $date
     * @param string $type
     * @param string $amount
     * @param string $currency
     */
    public function __construct($date, $type, $amount, $currency)
    {
        $this->setDate($date);
        $this->setType($type);
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }
    
    /**
     * Get date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Set date.
     *
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    /**
     * Get operation type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set operation type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Get amount.
     * 
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Set amount.
     * 
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    
    /**
     * Get currency.
     * 
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    
    /**
     * Set currency.
     * 
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    /**
     * Get week number. The week number is concatenation of the year and
     * week number of year, weeks starting on Monday.
     *  
     * @return string
     */
    public function getWeekNumber()
    {
        return date('oW', strtotime($this->date));
    }
}
