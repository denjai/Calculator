<?php

namespace App;

/**
 * Description of Operation
 *
 * @author Doncho Toromanov
 */
class Operation
{
    const CASH_OUT = 'cash_out';
    const CASH_IN = 'cash_in';
    
    private $date;
    
    private $type;
    
    private $amount;
    
    private $currency;
    
    public function __construct($date, $type, $amount, $currency)
    {
        $this->setDate($date);
        $this->setType($type);
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }
    
    public function getDate()
    {
        return $this->date;
    }
    
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    public function getWeekNumber()
    {
        return date('oW', strtotime($this->date));
    }
}
