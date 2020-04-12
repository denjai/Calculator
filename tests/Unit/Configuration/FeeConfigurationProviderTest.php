<?php

namespace Tests\Unit\Configuration;

use App\Configuration\FeeConfigurationProvider;
use PHPUnit\Framework\TestCase;

class FeeConfigurationProviderTest extends TestCase
{
    /**
     * @var \App\Configuration\FeeConfigurationProvider
     */
    private $provider;

    protected function setUp()
    {
        parent::setUp();

        $this->provider = new FeeConfigurationProvider('20', '30.7', ['250', 'GBP'], ['500', 'USD'], ['1000.56', 'EUR'], 7);
    }

    public function testGetWeeklyCashOutAmountLimit()
    {
        $this->assertEquals(['1000.56', 'EUR'], $this->provider->getWeeklyCashOutAmountLimit());
    }

    public function testGetCashInFeePercentage()
    {
        $this->assertEquals('30.7', $this->provider->getCashInFeePercentage());
    }

    public function testGetMaxCashInFee()
    {
        $this->assertEquals(['250', 'GBP'], $this->provider->getMaxCashInFee());
    }

    public function testGetWeeklyCashOutCountLimit()
    {
        $this->assertEquals(7, $this->provider->getWeeklyCashOutCountLimit());
    }

    public function testGetCashOutFeePercentage()
    {
        $this->assertEquals('20', $this->provider->getCashOutFeePercentage());
    }

    public function testGetMinCashOutFee()
    {
        $this->assertEquals(['500', 'USD'], $this->provider->getMinCashOutFee());
    }
}
