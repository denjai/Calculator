<?php

namespace Test\Unit\Factories;

use App\Factories\PersonFactory;
use App\LegalPerson;
use App\MoneyCalculator;
use App\NaturalPerson;
use PHPUnit\Framework\TestCase;

class PersonFactoryTest extends TestCase
{
    /**
     * @var \App\PersonFactory
     */
    private $factory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $calculator;

    public function setUp()
    {
        parent::setUp();

        $this->factory = new PersonFactory();
        $this->calculator = $this->createMock(MoneyCalculator::class);
    }

    /**
     * @dataProvider createProvider
     * @param string $type
     * @param int $id
     * @param \App\Person $expected
     * @throws \Exception
     */
    public function testCreate($type, $id, $expected)
    {
        $person = $this->factory->create($type, $id, $this->calculator);

        $this->assertInstanceOf($expected, $person);
    }

    /**
     * @dataProvider createInvalidDataProvider
     * @param string $type
     * @param int $id
     * @throws \Exception
     */
    public function testCreateWithInvalidData($type, $id)
    {
        $this->expectException(\Exception::class);

        $this->factory->create($type, $id, $this->calculator);
    }

    public function createProvider()
    {
        return [
            ['legal', 1, LegalPerson::class],
            ['natural', 4, NaturalPerson::class],
        ];
    }

    public function createInvalidDataProvider()
    {
        return [
            ['goodPerson', 1],
            ['badPerson', 4],
        ];
    }
}
