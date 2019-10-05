<?php


namespace App\Domain\Repositories;

use App\Domain\Repositories\IPersonRepository as Person;


interface IRefundRepository
{
    public function getAll();
    public function create(Person $person);
}
