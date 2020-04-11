<?php

namespace App\Repositories;

/**
 * PersonRepository
 *
 * @author Doncho Toromanov
 */
class PersonRepository implements PersonRepositoryInterface
{
    /**
     * @var array
     */
    private $persons;
    
    
    /**
     * Class constructor.
     */
    public function __construct()
    {
    }
    
    /**
     * Get person by ID.
     *
     * @param int $id
     *
     * @return \App\Person
     */
    public function getPerson($id)
    {
        return $this->persons[$id] ?? null;
    }
    
    /**
     * Add person.
     *
     * @param \App\Person $person
     */
    public function addPerson($person)
    {
        $this->persons[$person->getId()] = $person;
    }
    
    public function deleteAll()
    {
        $this->persons = [];
    }
}
