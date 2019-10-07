<?php


namespace App\Domain\Interfaces\Services;


interface IPersonService
{
    public function getAllPersons();

    public function getPerson($id);

    public function savePerson(array $data);

    public function updatePerson($personId, array $data);

    public function deletePerson($personId);

    public function getStoringValidationData(): array;

    public function getUpdatingValidationData(): array;
}
