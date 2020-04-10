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
    
    private $supportedCurrencies;
    
    /**
     * Class constructor.
     *
     * @param array $supportedCurrencies
     */
    public function __construct(array $supportedCurrencies)
    {
        $this->supportedCurrencies = $supportedCurrencies;
    }
    
    /**
     *
     * @param array $operations
     */
    public function validateOperations($operations)
    {
        foreach ($operations as $operation) {
            $this->validateOperation($operation);
        }
    }
    
    /**
     *
     * @param array $dataRow
     */
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
    
    /**
     *
     * @param string $date
     * @param string $format
     * @throws \InvalidArgumentException
     */
    public function validateDate($date, $format = self::DEFAULT_DATE_FORMAT)
    {
        $d = \DateTime::createFromFormat($format, $date);

        if (!$d || $d->format($format) !== $date) {
            throw new \InvalidArgumentException('Invalid date:' . $date);
        }
    }
    
    /**
     *
     * @param int $value
     * @throws \InvalidArgumentException
     */
    public function validateInteger($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('Invalid Integer:' . $value);
        }
    }
    
    /**
     *
     * @param string $type
     * @throws \InvalidArgumentException
     */
    public function validatePersonType($type)
    {
        if (!in_array($type, self::$validPersonTypes)) {
            throw new \InvalidArgumentException('Invalid person type:' . $type);
        }
    }
    
    /**
     *
     * @param string $currency
     * @throws \InvalidArgumentException
     */
    public function validateCurrency($currency)
    {
        if (!in_array($currency, $this->supportedCurrencies)) {
            throw new \InvalidArgumentException('Invalid or not supported currency:' . $currency);
        }
    }
    
    /**
     *
     * @param string $amount
     * @throws \InvalidArgumentException
     */
    public function validateMoneyAmount($amount)
    {
        $p = '/^([0-9]*\.?[0-9]*)$/';

        if (preg_match($p, $amount) !== 1) {
            throw new \InvalidArgumentException('Invalid money amount format:' . $amount);
        }
    }
}
