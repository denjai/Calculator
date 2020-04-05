<?php declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Calculator;

/**
 * Tets for Calculator class.
 *
 * @author Doncho Toromanov
 */
class CalculatorTest extends TestCase
{
    /**
     *
     * @param array $operations
     * @param array $expectedResult
     *
     * @dataProvider dataProviderCalculatFees
     */
    public function testCalculateFees($operations, $expectedResult)
    {
        $calculator = new Calculator($operations);
        $result = $calculator->getFees();

        $this->assertSame($expectedResult, $result);
    }
    
    public function dataProviderCalculatFees()
    {
        return [
            [
                [ //input operations
                    ['2014-12-31', 4, 'natural', 'cash_out', '1200.00', 'EUR'],
                    ['2015-01-01', 4, 'natural', 'cash_out', '1000.00', 'EUR'],
                    ['2016-01-05', 4, 'natural', 'cash_out', '1000.00' , 'EUR'],
                    ['2016-01-05', 1, 'natural', 'cash_in', '200.00', 'EUR'],
                    ['2016-01-06', 2, 'legal', 'cash_out', '300.00', 'EUR'],
                    ['2016-01-06', 1, 'natural', 'cash_out', '30000', 'JPY'],
                    ['2016-01-07', 1, 'natural', 'cash_out', '1000.00', 'EUR'],
                    ['2016-01-07', 1, 'natural', 'cash_out', '100.00', 'USD'],
                    ['2016-01-10', 1, 'natural', 'cash_out', '100.00', 'EUR'],
                    ['2016-01-10', 2, 'legal', 'cash_in', '1000000.00', 'EUR'],
                    ['2016-01-10', 3, 'natural', 'cash_out', '1000.00', 'EUR'],
                    ['2016-02-15', 1, 'natural', 'cash_out', '300.00', 'EUR'],
                    ['2016-02-19', 5, 'natural', 'cash_out', '3000000', 'JPY'],
                ],
                [ //expected commision fees
                    '0.60',
                    '3.00',
                    '0.00',
                    '0.06',
                    '0.90',
                    '0',
                    '0.70',
                    '0.30',
                    '0.30',
                    '5.00',
                    '0.00',
                    '0.00',
                    '8612',
                ]
            ]

        ];
    }
}
