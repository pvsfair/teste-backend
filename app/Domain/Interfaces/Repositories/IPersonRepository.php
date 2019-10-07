<?php


namespace App\Domain\Interfaces\Repositories;


interface IPersonRepository
{
    public function find($identification, $fixToShow = true);

    public function getAll();

    public function refunds();

    public function storePerson(array $all);

    public function updatePerson($person, array $all);

    public function removePerson($person);
}
