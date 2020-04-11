<?php declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Calculator;
use App\MoneyCalculator;
use App\Configuration\MoneyConfigurationProvider;
use App\Configuration\FeeConfigurationProvider;
use App\Validation\InputValidator;
use App\Repositories\PersonRepository;
use App\Factories\PersonFactory;

/**
 * Test for Calculator class.
 *
 * @author Doncho Toromanov
 */
class CalculatorTest extends TestCase
{
    /**
     * @var \App\Configuration\MoneyConfigurationProviderInterface
     */
    private $moneyConfigurationProvider;
    
    /**
     *
     * @var \App\Configuration\FeeConfigurationProviderInterface
     */
    private $feeConfigurationProvider;
    
    public function setUp()
    {
        parent::setUp();
        
        $currencyPrecisions = ['EUR' => 2, 'USD' => 2, 'JPY' => 0];
        $supportedCurrencies = ['EUR', 'USD', 'JPY'];
        $conversionRates = ['EUR:USD' => '1.1497', 'EUR:JPY' => '129.53'];

        $this->moneyConfigurationProvider = new MoneyConfigurationProvider($currencyPrecisions, $supportedCurrencies, $conversionRates);
        
        $this->feeConfigurationProvider =  new FeeConfigurationProvider('0.3', '0.03', ['5', 'EUR'], ['0.5', 'EUR'], ['1000', 'EUR'], 3);
    }
    /**
     *
     * @param array $operations
     * @param array $expectedResult
     *
     * @dataProvider dataProviderCalculateFees
     */
    public function testCalculateFees($operations, $expectedResult)
    {
        $validator = new InputValidator($this->moneyConfigurationProvider->getSupportedCurrencies());
        $moneyCalculator = new MoneyCalculator($this->moneyConfigurationProvider);
        $personRepository = new PersonRepository();
        $personFactory = new PersonFactory();
        $calculator = new Calculator($validator, $this->feeConfigurationProvider, $moneyCalculator, $personRepository, $personFactory);
        $result = $calculator->calculateFees($operations);

        $this->assertSame($expectedResult, $result);
    }
    
    public function dataProviderCalculateFees()
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
                [ //expected commission fees
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
