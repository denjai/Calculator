<?php

namespace Tests\Unit\Repositories;

use App\MoneyCalculator;
use App\Person;
use App\Repositories\PersonRepository;
use PHPUnit\Framework\TestCase;

class PersonRepositoryTest extends TestCase
{
    /**
     * @var \App\Repositories\PersonRepository
     */
    private $repository;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $calculator;

    protected function setUp()
    {
        parent::setUp();

        $this->repository = new PersonRepository();
        $this->calculator = $this->createMock(MoneyCalculator::class);
    }

    public function testAddGetPerson()
    {
        $person = new Person(4, $this->calculator);
        $this->repository->addPerson($person);
        $resultPerson = $this->repository->getPerson(4);

        $this->assertEquals($person, $resultPerson);
    }

    public function testDeleteAll()
    {
        $person = new Person(4, $this->calculator);
        $this->repository->addPerson($person);

        $this->repository->deleteAll();

        $resultPerson = $this->repository->getPerson(4);

        $this->assertNotEquals($person, $resultPerson);
    }
}
