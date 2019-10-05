<?php


namespace App\Domain\Repositories;


interface IPersonRepository
{
    //TODO: adicionar __call para poder chamar find com outros parametros alem da indentificacao

    public function find(int $identification);
    public function getAll();
    public function refunds();
}
