<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Validation\InputValidator;

/**
 * InputValidatorTest
 *
 * @author Doncho Toromanov
 */
class InputValidatorTest extends TestCase
{
    private $validator;
    
    public function setUp()
    {
        parent::setUp();
        
        /** @var InputValidator **/
        $this->validator = new InputValidator(['EUR', 'USD', 'JPY']);
    }
    
    /**
     * @dataProvider providerForInvalidDates
     * @expectedException \InvalidArgumentException
     * @param string $date
     * @param string $format
     */
    public function testValidateDateWithInvalidData($date, $format)
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->validator->validateDate($date, $format);
    }
    
    /**
     * @dataProvider providerForInvalidIntegers
     * @expectedException \InvalidArgumentException
     * @param int $value
     */
    public function testValidateIntegerWithInvalidData($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->validator->validateInteger($value);
    }
    
    /**
     * @dataProvider providerForInvalidCurrency
     * @expectedException \InvalidArgumentException
     * @param string $value
     */
    public function testValidateCurrencyWithInvalidData($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->validator->validateCurrency($value);
    }
    
    /**
     * @dataProvider providerForInvalidMoneyAmount
     * @expectedException \InvalidArgumentException
     * @param string $value
     */
    public function testValidateMoneyAmountWithInvalidData($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->validator->validateMoneyAmount($value);
    }
    
    public function providerForInvalidDates()
    {
        return [
            'invalid year' => ['2k-02-15', 'Y-m-d'],
            'invalid month' => ['2002-56-15', 'Y-m-d'],
            'invalid day' => ['2002-02-66', 'Y-m-d'],
            'invalid format' => ['2002.02.13', 'Y-m-d'],
            'invalid format 2' => ['2020-12-12', 'y-m-d'],
            ];
    }
    
    public function providerForInvalidIntegers()
    {
        return [
                ['2k-02-15'],
                ['102k442'],
                [23.96666],
                [0.36],
                ['ok']
            ];
    }
    
    public function providerForInvalidCurrency()
    {
        return [['GBP'], ['test'], ['invalid']];
    }
    
    public function providerForInvalidMoneyAmount()
    {
        return [['20 USD'], ['three dollars'], ['88,98']];
    }
}
