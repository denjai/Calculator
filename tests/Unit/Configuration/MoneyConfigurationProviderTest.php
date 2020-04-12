<?php

namespace Tests\Unit\Configuration;

use App\Configuration\MoneyConfigurationProvider;
use PHPUnit\Framework\TestCase;

class MoneyConfigurationProviderTest extends TestCase
{
    /**
     * @var \App\Configuration\MoneyConfigurationProvider
     */
    private $provider;

    /**
     * @var array
     */
    private $supportedCurrencies;

    protected function setUp()
    {
        parent::setUp();
        $currencyPrecisions = ['EUR' => 2, 'USD' => 2, 'JPY' => 0];
        $this->supportedCurrencies = ['EUR', 'USD', 'JPY'];
        $conversionRates = ['EUR:USD' => '1.1497', 'EUR:JPY' => '129.53', 'AB:CD' => '1234'];

        $this->provider = new MoneyConfigurationProvider($currencyPrecisions, $this->supportedCurrencies, $conversionRates);
    }

    /**
     * @dataProvider defaultPrecisionProvider
     * @param string $currency
     * @param int $expected
     */
    public function testGetDefaultPrecision($currency, $expected)
    {
        $this->assertEquals($expected, $this->provider->getDefaultPrecision($currency));
    }

    public function testGetDefaultPrecisionWithInvalidData()
    {
        $default = 5;
        $this->assertEquals($default, $this->provider->getDefaultPrecision('TEST', $default));
    }

    public function testGetSupportedCurrencies()
    {
        $this->assertEquals($this->supportedCurrencies, $this->provider->getSupportedCurrencies());
    }

    /**
     * @dataProvider conversionRatesProvider
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param string $expected
     */
    public function testGetConversionRate($currencyFrom, $currencyTo, $expected)
    {
        $this->assertEquals($expected, $this->provider->getConversionRate($currencyFrom, $currencyTo));
    }

    public function testGetConversionRateWithInvalidData()
    {
        $this->assertEquals(false, $this->provider->getConversionRate('money1', 'money2'));
    }

    public function defaultPrecisionProvider()
    {
        return [
            ['EUR', 2],
            ['USD', 2],
            ['JPY', 0],
        ];
    }

    public function conversionRatesProvider()
    {
        return [
            ['EUR', 'USD', '1.1497'],
            ['EUR', 'JPY', '129.53'],
            ['AB', 'CD', '1234'],
        ];
    }
}
