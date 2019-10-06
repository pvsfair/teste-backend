<?php


namespace App\Domain\Repositories;


interface IPersonRepository
{
    public function find($identification, $fixToShow = true);

    public function getAll();

    public function refunds();

    public function storePerson(array $all);

    public function updatePerson($person, array $all);

    public function removePerson($person);

    public function getStoringValidationData(): array;

    public function getUpdatingValidationData(): array;
}
