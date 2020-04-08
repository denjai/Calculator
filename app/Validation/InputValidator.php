<?php

namespace App\Validation;

/**
 * InputValidator
 *
 * @author Doncho Toromanov
 */
class InputValidator
{
    const DEFAULT_DATE_FORMAT = 'Y-m-d';
    
    protected static $inputTypes = ['date', 'int', 'person_type', 'operation_type', 'amount', 'currency'];
    
    protected static $validPersonTypes = ['legal', 'natural'];
    
    public function validateOperations($operations)
    {
        foreach ($operations as $operation) {
            $this->validateOperation($operation);
        }
    }

    public function validateOperation($dataRow)
    {
        foreach ($dataRow as $key => $field) {
            switch (self::$inputTypes[$key]) {
                case 'date':
                    $this->validateDate($field);
                    break;
                case 'int':
                    $this->validateInteger($field);
                    break;
                case 'person_type':
                    $this->validatePersonType($field);
                    break;
                case 'currency':
                    $this->validateCurrency($field);
                    break;
                case 'amount':
                    $this->validateMoneyAmount($field);
                    break;
                default:
                    break;
            }
        }
    }
    
    public function validateDate($date, $format = self::DEFAULT_DATE_FORMAT)
    {
        $d = \DateTime::createFromFormat($format, $date);

        if (!$d || $d->format($format) !== $date) {
            throw new \InvalidArgumentException('Invalid date:' . $date);
        }
    }
    
    public function validateInteger($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Invalid Integer:' . $value);
        }
    }
    
    public function validatePersonType($type)
    {
        if (!in_array($type, self::$validPersonTypes)) {
            throw new \InvalidArgumentException('Invalid person type:' . $type);
        }
    }
    
    public function validateCurrency($currency)
    {
        $configProvider = new \App\Configuration\MoneyConfigurationProvider();
        if (!in_array($currency, $configProvider->getSupportedCurrencies())) {
            throw new \InvalidArgumentException('Invalid or not supported currency:' . $currency);
        }
    }
    
    public function validateMoneyAmount($amount)
    {
        $p = '/^([0-9]*\.?[0-9]*)$/';

        if (preg_match($p, $amount) !== 1) {
            throw new \InvalidArgumentException('Invalid money amount format:' . $amount);
        }
    }
}
