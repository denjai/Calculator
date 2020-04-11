<?php

namespace App\Repositories;

/**
 * PersonRepositoryInterface
 *
 * @author Doncho Toromanov
 */
interface PersonRepositoryInterface
{
    /**
     * Get person by ID.
     *
     * @param int $id
     *
     * @return \App\Person
     */
    public function getPerson($id);
    
    /**
     * Add person.
     *
     * @param \App\Person $person
     */
    public function addPerson($person);
    
    /**
     * Delete all data;
     */
    public function deleteAll();
}
