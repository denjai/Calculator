<?php
namespace Tests\Unit;

use App\Configuration\MoneyConfigurationProvider;
use App\MoneyCalculator;
use PHPUnit\Framework\TestCase;

class MoneyCalculatorTest extends TestCase
{
    /**
     * @var \App\MoneyCalculator
     */
    private $calculator;

    /**
     * Set up
     */
    public function setUp()
    {
        parent::setUp();

        $currencyPrecisions = ['EUR' => 2, 'USD' => 2, 'JPY' => 0, 'IQD' => 3];
        $supportedCurrencies = ['EUR', 'USD', 'JPY'];
        $conversionRates = ['EUR:USD' => '1.1497', 'EUR:JPY' => '129.53'];

        $configurationProvider = new MoneyConfigurationProvider($currencyPrecisions, $supportedCurrencies, $conversionRates);

        $this->calculator = new MoneyCalculator($configurationProvider, 6);
    }

    /**
     * @dataProvider compareProvider
     * @param string $leftOperand
     * @param string $rightOperand
     * @param int $expected
     */
    public function testCompare($leftOperand, $rightOperand, $expected)
    {
        $this->assertEquals($expected, $this->calculator->compare($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider multiplyProvider
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expected
     */
    public function testMultiply($leftOperand, $rightOperand, $expected)
    {
        $this->assertEquals($expected, $this->calculator->multiply($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider subtractProvider
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expected
     */
    public function testSubtract($leftOperand, $rightOperand, $expected)
    {
        $this->assertEquals($expected, $this->calculator->subtract($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider divideProvider
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expected
     */
    public function testDivide($leftOperand, $rightOperand, $expected)
    {
        $this->assertEquals($expected, $this->calculator->divide($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider addProvider
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expected
     */
    public function testAdd($leftOperand, $rightOperand, $expected)
    {
        $this->assertEquals($expected, $this->calculator->add($leftOperand, $rightOperand));
    }

    /**
     * @dataProvider roundProvider
     * @param string $value
     * @param string $currency
     * @param string $expected
     */
    public function testRound($value, $currency, $expected)
    {
        $this->assertEquals($expected, $this->calculator->round($value, $currency));
    }

    /**
     * @dataProvider convertProvider
     * @param string $value
     * @param string $currFrom
     * @param string $currTo
     * @param string $expected
     * @throws \Exception
     */
    public function testConvert($value, $currFrom, $currTo, $expected)
    {
        $this->assertEquals($expected, $this->calculator->convert($value, $currFrom, $currTo));
    }

    /**
     * @dataProvider convertInvalidDataProvider
     * @param $value
     * @param $currFrom
     * @param $currTo
     * @throws \Exception
     */
    public function testConvertWithInvalidData($value, $currFrom, $currTo)
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->calculator->convert($value, $currFrom, $currTo);
    }

    public function compareProvider()
    {
        return [
            ['5', '2', 1],
            ['-2', '-15', 1],
            ['-18.222222', '-18.222221', -1],
            ['-44.222222', '-44.2222223', 0],
        ];
    }

    public function addProvider()
    {
        return [
            ['1', '1', '2'],
            ['-1', '-1', '-2'],
            ['1', '-1', '0'],
            ['-1', '1', '0'],
            ['0.5', '0.5', '1'],
            ['-0.5', '-0.5', '-1'],
            ['-0.5', '0', '-0.5'],
            ['-0', '0', '0'],
            ['1', '2', '3'],
            ['-2212.999329', '-22441.12123', '-24654.120559'],
            ['-2212.999329', '22441.12123', '20228.121901'],
            ['9223372036854775807', '1', '9223372036854775808'],
            ['-9223372036854775807', '-1', '-9223372036854775808'],
            ['-9223372036854775808.021', '9223372036854775809.221002', '1.200002'],
            ['1.1234567', '2', '3.123456'],
        ];
    }

    public function multiplyProvider()
    {
        return [
            ['1', '1', '1'],
            ['-1', '-1', '1'],
            ['1', '-1', '-1'],
            ['-1', '1', '-1'],
            ['0.5', '0.5', '0.25'],
            ['-0.5', '-0.5', '0.25'],
            ['-0.5', '0', '0'],
            ['-0', '0', '0'],
            ['3', '2', '6'],
            ['29.98001', '213121212123.553', '6389376070676.240175'],
        ];
    }

    public function subtractProvider()
    {
        return [
            ['1', '1', '0'],
            ['-1', '-1', '0'],
            ['1', '-1', '2'],
            ['-1', '1', '-2'],
            ['0.5', '0.5', '0'],
            ['-0.5', '-0.5', '0'],
            ['-0.5', '0', '-0.5'],
            ['-0', '0', '0'],
        ];
    }

    public function divideProvider()
    {
        return [
            ['1', '1', '1'],
            ['1', '2', '0.5'],
            ['1', '3', '0.333333'],
            ['-1', '-1', '1'],
            ['1', '-1', '-1'],
            ['-1', '1', '-1'],
            ['0.5', '0.5', '1'],
            ['-0.5', '-0.5', '1'],
            ['35', '3.5', '10'],
        ];
    }

    public function roundProvider()
    {
        return [
            ['10.544', 'EUR', '10.55'],
            ['-22.544', 'USD', '-22.54'],
            ['12.545', 'EUR', '12.55'],
            ['6.444444', 'JPY', '7'],
            ['5.488888', 'JPY', '6'],
            ['10.588888', 'JPY', '11'],
            ['0', 'JPY', '0'],
            ['0', 'EUR', '0'],
            ['10.545', 'IQD', '10.545'],
        ];
    }

    public function convertProvider()
    {
        return [
            ['234.45', 'EUR', 'EUR', '234.45'],
            ['222.45', 'EUR', 'USD', '255.750765'],
            ['255.750765', 'USD', 'EUR', '222.45'],
        ];
    }

    public function convertInvalidDataProvider()
    {
        return [
            ['234.45', 'EUR', 'money'],
            ['222.45', '', 'USD'],
            ['25', 'EUR', ''],
        ];
    }
}
